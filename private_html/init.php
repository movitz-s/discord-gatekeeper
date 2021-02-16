<?php

session_start();
require_once 'env.php';
require_once 'pdo.php';
require_once 'http.php';
require_once 'klasser.php';

$db = new DB(DB_NAME, DB_USER, DB_PASSWORD);
$client = new SimpleHTTPClient();
