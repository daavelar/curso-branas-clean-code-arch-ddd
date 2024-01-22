<?php

const INVALID_CPF = -1;
const INVALID_EMAIL = -2;
const INVALID_NAME = -3;
const EMAIL_ALREADY_EXISTS = -4;
const INVALID_CAR_PLATE = -5;

function signup($input)
{
    $conn = connectToMysql();

    $id = generateId();

    if (emailAlreadyExists($conn, $input['email'])) {
        return EMAIL_ALREADY_EXISTS;
    }
    if (!nameIsValid($input['name'])) {
        return INVALID_NAME;
    }
    if (!emailIsValid($input['email'])) {
        return INVALID_EMAIL;
    }
    if (!cpfIsValid($input['cpf'])) {
        return INVALID_CPF;
    }
    if (!carPlateIsValid($input['carPlate'])) {
        return INVALID_CAR_PLATE;
    }

    createUser($conn, [
        'id' => $id,
        'name' => $input['name'],
        'email' => $input['email'],
        'cpf' => $input['cpf'],
        'carPlate' => $input['carPlate'],
        'isPassenger' => $input['isPassenger'] ?? false,
        'isDriver' => $input['isDriver'] ? true : false,
    ]);

    return ['accountId' => $id];
}

function createUser($conn, $user): void
{
    $stmt = $conn->prepare(
        "INSERT INTO account (account_id, name, email, cpf, car_plate, is_passenger, is_driver) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $user['id'],
        $user['name'],
        $user['email'],
        $user['cpf'],
        $user['carPlate'],
        intval($user['isPassenger']),
        intval($user['isDriver'])
    ]);
}

function carPlateIsValid($carPlate): bool
{
    return preg_match("/[A-Z]{3}[0-9]{4}/", $carPlate);
}

function emailIsValid($email): mixed
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function nameIsValid($name): bool
{
    return preg_match("/[a-zA-Z] [a-zA-Z]+/", $name);
}

function emailAlreadyExists(PDO $conn, string $email): bool
{
    $stmt = $conn->prepare("SELECT * FROM cccat15.account WHERE email = :email");
    $stmt->execute(['email' => $email]);

    return (bool)$stmt->fetch();
}

function generateId(): string
{
    return bin2hex(random_bytes(16));
}