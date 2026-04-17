<?php
class Database{
    private $serverName = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbName = 'physics_hub';
    private $legacySalt = 'aqwertybgfdhuio15527890ytahsfwretabmopiujhg456278hsfarwtqvcbfghsioiowuytshdgfvarefdhjoi10987gdbbabaoljhgadsf';

    private function ServerName(){
        return getenv('DB_HOST') ?: $this->serverName;
    }

    private function UserName(){
        return getenv('DB_USER') ?: $this->username;
    }

    private function Password(){
        $envPassword = getenv('DB_PASS');
        if ($envPassword !== false) {
            return $envPassword;
        }
        return $this->password;
    }

    private function DBName(){
        return getenv('DB_NAME') ?: $this->dbName;
    }

    private function LegacyHashPassword($pw){
        return hash_hmac('sha256', $pw, $this->legacySalt);
    }

    private function GetConnection(){
        $this->AssertProductionSafeConfig();
        $conn = new PDO(
            "mysql:host=".$this->ServerName().";dbname=".$this->DBName().";charset=utf8mb4",
            $this->UserName(),
            $this->Password()
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $conn;
    }

    private function AssertProductionSafeConfig(){
        $env = strtolower((string)(getenv('APP_ENV') ?: 'development'));
        $isProduction = in_array($env, ['prod', 'production'], true);

        if (!$isProduction) {
            return;
        }

        $username = $this->UserName();
        $password = $this->Password();

        if ($username === 'root') {
            throw new RuntimeException('Unsafe DB configuration: root user is not allowed in production.');
        }

        if ($password === '') {
            throw new RuntimeException('Unsafe DB configuration: empty DB password is not allowed in production.');
        }
    }

    private function QuoteIdentifier($identifier){
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier)) {
            throw new InvalidArgumentException('Invalid SQL identifier');
        }
        return '`'.$identifier.'`';
    }

    private function BuildCriteria(array $criteria, array &$values){
        if (empty($criteria)) {
            return '';
        }

        $clauses = [];
        foreach($criteria as $key=>$value) {
            $clauses[] = $this->QuoteIdentifier($key).' = ?';
            $values[] = $value;
        }
        return ' WHERE '.implode(' AND ', $clauses);
    }

    private function SanitizeSnippet($snippet){
        if ($snippet === null) {
            return '';
        }

        $snippet = trim($snippet);
        if ($snippet === '') {
            return '';
        }

        $isValid = preg_match(
            '/^(ORDER BY\s+[A-Za-z_][A-Za-z0-9_]*(\s+(ASC|DESC))?(\s*,\s*[A-Za-z_][A-Za-z0-9_]*(\s+(ASC|DESC))?)*(\s+LIMIT\s+\d+(\s*,\s*\d+)?)?|LIMIT\s+\d+(\s*,\s*\d+)?)$/i',
            $snippet
        );

        if (!$isValid) {
            throw new InvalidArgumentException('Invalid SQL snippet');
        }

        return ' '.$snippet;
    }

    public function FilterInput($data){
        $data = trim((string)$data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    public function HashPassword($pw){
        return password_hash((string)$pw, PASSWORD_DEFAULT);
    }

    public function VerifyPassword($plainPassword, $storedHash){
        if (!is_string($storedHash) || $storedHash === '') {
            return false;
        }

        if (password_verify((string)$plainPassword, $storedHash)) {
            return true;
        }

        return hash_equals($this->LegacyHashPassword((string)$plainPassword), $storedHash);
    }

    public function NeedsRehash($storedHash){
        return password_get_info((string)$storedHash)['algo'] === 0;
    }

    public function Insert($table, $data){
        try{
            if (empty($data)) {
                throw new InvalidArgumentException('Insert data cannot be empty');
            }

            $columns = []; $values = []; $insertTokens = [];
            foreach($data as $key=>$value){
                $columns[] = $this->QuoteIdentifier($key);
                $insertTokens[] = '?';
                $values[] = $value;
            }

            $conn = $this->GetConnection();
            $sql = "INSERT INTO ".$this->QuoteIdentifier($table)." (".implode(',', $columns).") VALUES (".implode(',', $insertTokens).")";
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }catch(Throwable $e){
            return $e->getMessage();
        }
    }

    public function Update($table, $data, $criteria){
        try {
            if (empty($data) || empty($criteria)) {
                throw new InvalidArgumentException('Update requires data and criteria');
            }

            $sets = []; $values = [];
            foreach($data as $key=>$value) {
                $sets[] = $this->QuoteIdentifier($key).' = ?';
                $values[] = $value;
            }

            $criteriaValues = [];
            $whereSql = $this->BuildCriteria($criteria, $criteriaValues);
            $values = array_merge($values, $criteriaValues);

            $conn = $this->GetConnection();
            $sql = "UPDATE ".$this->QuoteIdentifier($table)." SET ".implode(",", $sets).$whereSql;
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }
        catch(Throwable $e)
        {
            return $e->getMessage();
        }
    }

    public function Delete($table, $criteria){
        try {
            if (empty($criteria)) {
                throw new InvalidArgumentException('Delete requires criteria');
            }

            $values = [];
            $whereSql = $this->BuildCriteria($criteria, $values);

            $conn = $this->GetConnection();
            $sql = "DELETE FROM ".$this->QuoteIdentifier($table).$whereSql;
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }
        catch(Throwable $e)
        {
            return $e->getMessage();
        }
    }

    public function Fetch($table){
        try {
            $conn = $this->GetConnection();
            $stmt = $conn->prepare("SELECT * FROM ".$this->QuoteIdentifier($table));
            $stmt->execute();
            $rows = $stmt->fetchAll();
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchAllWithCriteria($table, array $criteria, $snippet=null){
        try {
            $conn = $this->GetConnection();
            $values = [];
            $whereSql = $this->BuildCriteria($criteria, $values);
            $safeSnippet = $this->SanitizeSnippet($snippet);
            $stmt = $conn->prepare("SELECT * FROM ".$this->QuoteIdentifier($table).$whereSql.$safeSnippet);
            $stmt->execute($values);
            $rows = $stmt->fetchAll();
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchSomeWithCriteria($table, array $columns, array $criteria, $snippet=null){
        try {
            if (empty($columns)) {
                throw new InvalidArgumentException('Columns cannot be empty');
            }

            $safeColumns = [];
            foreach ($columns as $column) {
                $safeColumns[] = $this->QuoteIdentifier($column);
            }

            $values = [];
            $whereSql = $this->BuildCriteria($criteria, $values);
            $safeSnippet = $this->SanitizeSnippet($snippet);

            $conn = $this->GetConnection();
            $stmt = $conn->prepare(
                "SELECT ".implode(',', $safeColumns)." FROM ".$this->QuoteIdentifier($table).$whereSql.$safeSnippet
            );
            $stmt->execute($values);
            $rows = $stmt->fetchAll();
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchDistinctWithcriteria($table, $column, array $criteria, $snippet=null){
        try {
            $safeColumn = $this->QuoteIdentifier($column);
            $values = [];
            $whereSql = $this->BuildCriteria($criteria, $values);
            $safeSnippet = $this->SanitizeSnippet($snippet);

            $conn = $this->GetConnection();
            $stmt = $conn->prepare(
                "SELECT DISTINCT(".$safeColumn.") AS ".$safeColumn." FROM ".$this->QuoteIdentifier($table).$whereSql.$safeSnippet
            );
            $stmt->execute($values);
            $rows = $stmt->fetchAll();
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchDistinct($table, $column, $snippet=null){
        try {
            $safeColumn = $this->QuoteIdentifier($column);
            $safeSnippet = $this->SanitizeSnippet($snippet);

            $conn = $this->GetConnection();
            $stmt = $conn->prepare(
                "SELECT DISTINCT(".$safeColumn.") AS ".$safeColumn." FROM ".$this->QuoteIdentifier($table).$safeSnippet
            );
            $stmt->execute();
            $rows = $stmt->fetchAll();
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }
}
