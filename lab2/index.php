<?php
require_once("./config.php");
require_once("./MySQLHandler.php");
require_once("./functions.php");

$dbconnect = new MySQLHandler("products");
$testConnect = $dbconnect->connect();

if ($testConnect) {
    $product_id=getUrl();
    handle_requests($dbconnect,$product_id);
} else {
    $response = ["error" => "database Not connected."];
    http_response_code(500);
    header('Content-Type: application/json');}


