<?php

const CPF_LENGTH = 11;

function cpfIsValid($rawCpf): bool
{
    if (!$rawCpf) {
        return false;
    }
    $cpf = removeNonDigits($rawCpf);
    if (isInvalidLength($cpf)) {
        return false;
    }
    if (hasAllDigitsEqual($cpf)) {
        return false;
    }

    $digit1 = calculateDigit($cpf, 10);
    $digit2 = calculateDigit($cpf, 11);

    return extractDigit($cpf) === "{$digit1}{$digit2}";
}

function removeNonDigits($cpf): string
{
    return preg_replace('/\D/', '', $cpf);
}

function isInvalidLength($cpf): bool
{
    return strlen($cpf) !== CPF_LENGTH;
}

function hasAllDigitsEqual($cpf): bool
{
    $firstCpfDigit = $cpf[0];
    return str_split($cpf) === array_fill(0, strlen($cpf), $firstCpfDigit);
}

function calculateDigit($cpf, $factor): string
{
    $total = 0;
    for ($i = 0; $i < strlen($cpf); $i++) {
        if ($factor > 1) {
            $total += intval($cpf[$i]) * $factor--;
        }
    }
    $rest = $total % 11;
    return ($rest < 2) ? 0 : 11 - $rest;
}

function extractDigit($cpf): string
{
    return substr($cpf, 9);
}

