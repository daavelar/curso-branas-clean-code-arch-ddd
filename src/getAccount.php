<?php

function getAccount($id)
{
    $conn = new PDO("mysql:host=localhost;dbname=cccat15", "root", "q1w2r4e3");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM account WHERE account_id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}