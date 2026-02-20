<?php

require_once __DIR__ . '/../utils/GenPassword.php';

class PasswordService
{
    public function generate(array $params): string
    {
        $length = $params['length'] ?? 16;

        if ($length < 4 || $length > 128) {
            throw new InvalidArgumentException("La longitud debe estar entre 4 y 128.");
        }

        return generate_password($length, $this->mapOptions($params));
    }

    public function generateMultiple(array $params): array
    {
        $count = $params['count'] ?? 1;
        $length = $params['length'] ?? 16;

        if ($count < 1) {
            throw new InvalidArgumentException("Count debe ser >= 1");
        }

        if ($length < 4 || $length > 128) {
            throw new InvalidArgumentException("La longitud debe estar entre 4 y 128.");
        }

        return generate_passwords($count, $length, $this->mapOptions($params));
    }

    private function mapOptions(array $params): array
    {
        return [
            'upper' => $params['includeUppercase'] ?? true,
            'lower' => $params['includeLowercase'] ?? true,
            'digits' => $params['includeNumbers'] ?? true,
            'symbols' => $params['includeSymbols'] ?? true,
            'avoid_ambiguous' => $params['avoidAmbiguous'] ?? true,
            'exclude' => $params['exclude'] ?? '',
            'require_each' => true
        ];
    }
    public function validate(string $password, array $requirements): array
    {
        $errors = [];

        if (strlen($password) < ($requirements['minLength'] ?? 8)) {
            $errors[] = "Longitud insuficiente";
        }

        if (!empty($requirements['requireUppercase']) && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Debe contener mayúscula";
        }

        if (!empty($requirements['requireNumbers']) && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Debe contener número";
        }

        if (!empty($requirements['requireSymbols']) && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Debe contener símbolo";
        }

        return [
            "valid" => empty($errors),
            "errors" => $errors
        ];
    }
}