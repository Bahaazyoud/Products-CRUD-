<?php

$connection = new PDO('mysql:host=localhost;dbname=product_crud', 'root', '');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
