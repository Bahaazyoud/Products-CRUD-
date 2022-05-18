<?php

require 'connection.php';

$id =$_POST['id'] ?? null;
if(!$id){
    header('Location:index.php');
    exit;
}

$delete=$connection->prepare('DELETE FROM products WHERE id=:id');
$delete->execute(array(
    ':id'=>$id
));
header('Location:index.php');