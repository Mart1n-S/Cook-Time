<?php
$bddDNS = 'mysql:host=localhost;dbname=nameDataBase';
$bddUser = 'userName';
$bddMotDePasse = 'passWord';
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false
];
