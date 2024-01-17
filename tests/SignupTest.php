<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

class SignupTest extends TestCase
{
    private array $driver;

    protected function setUp(): void
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=cccat15", "root", "q1w2r4e3");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $this->conn->prepare('TRUNCATE TABLE account');
        $stmt->execute();

        $this->driver = [
            'id' => 'some-uuid',
            'name' => 'Some Driver',
            'email' => 'email@test.com',
            'isPassenger' => intval(false),
            'isDriver' => intval(true),
            'carPlate' => 'FRK7462',
            'cpf' => '98431210079',
        ];

        parent::setUp();
    }

    public function test_throw_error_if_email_already_exists()
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO account (account_id, name, email, cpf, car_plate, is_passenger, is_driver) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $this->driver['id'],
            $this->driver['name'],
            $this->driver['email'],
            $this->driver['cpf'],
            $this->driver['carPlate'],
            $this->driver['isPassenger'],
            $this->driver['isDriver']
        ]);

        $input = ['email' => $this->driver['email']];

        $output = signup($input);

        $this->assertEquals(EMAIL_ALREADY_EXISTS, $output);
    }

    public function test_throw_error_if_name_contain_numbers()
    {
        unset($this->driver['id']);
        $this->driver['name'] = '123 Oliveira 4';

        $output = signup($this->driver);

        $this->assertEquals(INVALID_NAME, $output);
    }

    public function test_throw_error_if_email_is_invalid()
    {
        unset($this->driver['id']);
        $this->driver['email'] = 'invalid-email';

        $output = signup($this->driver);

        $this->assertEquals(INVALID_EMAIL, $output);
    }


    public function test_throw_error_if_cpf_is_invalid()
    {
        unset($this->driver['id']);
        $this->driver['cpf'] = '012345';

        $output = signup($this->driver);

        $this->assertEquals(INVALID_CPF, $output);
    }

    public function test_return_account_id_if_signup_is_completed()
    {
        $output = signup($this->driver);

        $account = getAccount($output['accountId']);

        $this->assertEquals(['accountId' => $account['account_id']], $output);
    }

    public function test_return_error_code_if_car_plate_is_invalid()
    {
        unset($this->driver['id']);
        $this->driver['carPlate'] = 'invalid-plate';

        $output = signup($this->driver);

        $this->assertEquals(INVALID_CAR_PLATE, $output);
    }
}