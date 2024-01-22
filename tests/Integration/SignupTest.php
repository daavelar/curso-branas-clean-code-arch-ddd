<?php

namespace Tests\Integration;

use Tests\BaseTestCase;
use Tests\RefreshDatabase;

class SignupTest extends BaseTestCase
{
    use RefreshDatabase;

    private array $driverInput;

    protected function setUp(): void
    {
        $this->driverInput = [
            'name' => 'Some Driver',
            'email' => 'email@test.com',
            'isPassenger' => intval(false),
            'isDriver' => intval(true),
            'carPlate' => 'FRK7462',
            'cpf' => '98431210079',
        ];

        $this->refresh();

        parent::setUp();
    }

    public function test_throw_error_if_email_already_exists()
    {
        signup($this->driverInput);
        $input = ['email' => $this->driverInput['email']];
        $response = $this->post('http://localhost:9501/signup', $input);
        $this->assertEquals(EMAIL_ALREADY_EXISTS, $response);
    }

    public function test_throw_error_if_name_contain_numbers()
    {
        unset($this->driverInput['id']);
        $this->driverInput['name'] = '123 Oliveira 4';
        $response = $this->post('http://localhost:9501/signup', $this->driverInput);
        $this->assertEquals(INVALID_NAME, $response);
    }

    public function test_throw_error_if_email_is_invalid()
    {
        unset($this->driverInput['id']);
        $this->driverInput['email'] = 'invalid-email';

        $response = $this->post('http://localhost:9501/signup', $this->driverInput);

        $this->assertEquals(INVALID_EMAIL, $response);
    }


    public function test_throw_error_if_cpf_is_invalid()
    {
        unset($this->driverInput['id']);
        $this->driverInput['cpf'] = '012345';
        $response = $this->post('http://localhost:9501/signup', $this->driverInput);
        $this->assertEquals(INVALID_CPF, $response);
    }

    public function test_return_account_id_if_signup_is_completed()
    {
        $response = $this->post('http://localhost:9501/signup', $this->driverInput);
        $account = getAccount($response['accountId']);
        $this->assertEquals(['accountId' => $account['account_id']], $response);
    }

    public function test_return_account_id_if_signup_of_passenger_is_completed()
    {
        $this->driverInput['isPassenger'] = true;
        $this->driverInput['isDriver'] = false;
        $this->driverInput['carPlate'] = null;
        $response = $this->post('http://localhost:9501/signup', $this->driverInput);
        $account = getAccount($response['accountId']);
        $this->assertEquals(['accountId' => $account['account_id']], $response);
    }

    public function test_return_error_code_if_car_plate_is_invalid()
    {
        unset($this->driverInput['id']);
        $this->driverInput['carPlate'] = 'invalid-plate';
        $response = $this->post('http://localhost:9501/signup', $this->driverInput);
        $this->assertEquals(INVALID_CAR_PLATE, $response);
    }
}