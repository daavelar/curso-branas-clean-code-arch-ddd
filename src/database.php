<?php

function connectToMysql(): PDO
{
    $conn = new PDO("mysql:host=localhost;dbname=cccat15", getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
}