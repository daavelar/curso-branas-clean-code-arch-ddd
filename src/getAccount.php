<?php

function getAccount($id)
{
    $conn = connectToMysql();
    $stmt = $conn->prepare("SELECT * FROM account WHERE account_id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}