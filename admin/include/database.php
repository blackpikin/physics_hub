<?php
class Database{
    private $serverName = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbName = 'physics_hub';
    private function ServerName(){ return $this->serverName;}
    private function UserName(){return $this->username;}
    private function Password(){return $this->password;}
    private function DBName(){return $this->dbName;}

    public function FilterInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function HashPassword($pw){
        $salt = 'aqwertybgfdhuio15527890ytahsfwretabmopiujhg456278hsfarwtqvcbfghsioiowuytshdgfvarefdhjoi10987gdbbabaoljhgadsf';
        return hash_hmac('sha256', $pw, $salt);
    }

    public function Insert($table, $data){
        $columns = []; $values = []; $insertTokens = [];
        foreach($data as $key=>$value){
            array_push($columns, $key);
            array_push($insertTokens, '?');
            array_push($values, $value);
        }
        $cols = implode(',', $columns);
        $sql_values = implode(',', $insertTokens);
        try{
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $table ($cols) VALUES ($sql_values)";
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }catch(PDOException $e){
            $conn = null;
            return $e->getMessage();
        }

    }

    public function Update($table, $data, $criteria){
        $columns = []; $values =[]; $crit =[];
        foreach($data as $key=>$value) {
            array_push($columns, $key.'=?');
            array_push($values, $value);
        }
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_cols = implode(",", $columns);
        $sql_criteria = implode(' AND ', $crit);
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE $table SET ".$sql_cols." WHERE ".$sql_criteria;
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function Delete($table, $criteria){
        $crit = [];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM $table WHERE ".$sql_criteria;
            $statement = $conn->prepare($sql);
            $statement->execute($values);
            $conn = null;
            return "Successful";
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function Fetch($table){
        $rows = [];
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM $table");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchAllWithCriteria($table, array $criteria, $snippet=null){
        $crit =[];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM $table WHERE ".$sql_criteria." ".$snippet);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchSomeWithCriteria($table, array $columns, array $criteria, $snippet=null){
        $crit =[]; $cols = [];
        $cols = implode(',', $columns);
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT $cols FROM $table WHERE ".$sql_criteria." ".$snippet);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchDistinctWithcriteria($table, $column, array $criteria, $snippet=null){
        $crit =[];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT DISTINCT($column) FROM $table WHERE ".$sql_criteria." ".$snippet);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }

    public function FetchDistinct($table, $column, $snippet=null){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DBName(), $this->UserName(), $this->Password());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT DISTINCT($column) FROM $table $snippet");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;
        return $rows;
    }
    
}