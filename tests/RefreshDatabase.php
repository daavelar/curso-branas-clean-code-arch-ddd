<?php

namespace Tests;

use PDO;

trait RefreshDatabase
{
    public function refresh()
    {
        $conn = new PDO("mysql:host=localhost;dbname=cccat15", "root", "q1w2r4e3");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("TRUNCATE TABLE account");
        $stmt->execute();
    }
}