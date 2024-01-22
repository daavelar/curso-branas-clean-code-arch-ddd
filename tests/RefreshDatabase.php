<?php

namespace Tests;

use PDO;

trait RefreshDatabase
{
    public function refresh()
    {
        $conn = connectToMysql();
        $stmt = $conn->prepare("TRUNCATE TABLE account");
        $stmt->execute();
    }
}