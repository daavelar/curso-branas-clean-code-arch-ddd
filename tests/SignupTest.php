<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class SignupTest extends TestCase
{
    protected function setUp(): void
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=cccat15", "root", "q1w2r4e3");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        parent::setUp();
    }

    public function test_throw_error_if_email_already_exists()
    {
        $id = 'some-uuid';
        $name = 'Some Driver';
        $email = 'email@test.com';
        $isPassenger = false;
        $isDriver = false;
        $carPlate = 'FRK7462';
        $cpf = '98431210079';

        $stmt = $this->conn->prepare(
            "INSERT INTO cccat15.account (account_id, name, email, cpf, car_plate, is_passenger, is_driver) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$id, $name, $email, $cpf, $carPlate, $isPassenger, $isDriver]);

        $input = [
            'email' => $email
        ];

        signup($input);


    }
}