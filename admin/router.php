<?php
$qid = $_GET['qid'];
$qtyp = $_GET['qtyp'];
$action = $_GET['act'];

if($qtyp == 'simple' && $action == 'modify'){
    echo '<script>window.location.href="addQuestion.php?qid='.$qid.'"</script>';
}elseif($qtyp == 'simple2' && $action == 'modify'){
    echo '<script>window.location.href="addQuestion2.php?qid='.$qid.'"</script>';
}elseif($qtyp == 'cmc' && $action == 'modify'){
    echo '<script>window.location.href="addQuestion3.php?qid='.$qid.'"</script>';
}elseif($qtyp == 'simple2' && $action == 'attach'){
    echo '<script>window.location.href="attachSimple.php?qid='.$qid.'"</script>';
}elseif($qtyp == 'cmc' && $action == 'attach'){
    echo '<script>window.location.href="attachCmc.php?qid='.$qid.'"</script>';
}