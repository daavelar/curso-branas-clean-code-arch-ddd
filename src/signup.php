<?php
require_once 'validateCpf.php';

function signup($input) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=cccat15", "root", "q1w2r4e3");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = bin2hex(random_bytes(16));

        $stmt = $conn->prepare("SELECT * FROM cccat15.account WHERE email = :email");
        $stmt->execute(['email' => $input['email']]);
        $acc = $stmt->fetch();

        if (!$acc) {
            if (preg_match("/[a-zA-Z] [a-zA-Z]+/", $input['name'])) {
                if (filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                    if (validateCpf($input['cpf'])) {
                        $isDriver = $input['isDriver'] ? true : false;
                        $isPassenger = isset($input['isPassenger']) ? $input['isPassenger'] : false;

                        if ($isDriver && !preg_match("/[A-Z]{3}[0-9]{4}/", $input['carPlate'])) {
                            return -5;
                        }

                        $stmt = $conn->prepare("INSERT INTO cccat15.account (account_id, name, email, cpf, car_plate, is_passenger, is_driver) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$id, $input['name'], $input['email'], $input['cpf'], $input['carPlate'], $isPassenger, $isDriver]);

                        return ['accountId' => $id];
                    } else {
                        // CPF inválido
                        return -1;
                    }
                } else {
                    // Email inválido
                    return -2;
                }
            } else {
                // Nome inválido
                return -3;
            }
        } else {
            // Já existe
            return -4;
        }
    } catch (PDOException $e) {
        // Tratar exceção
    } finally {
        if (isset($conn)) {
            $conn = null; // Fecha a conexão com o banco
        }
    }
}
?>
