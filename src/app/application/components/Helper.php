<?php

declare(strict_types=1);

namespace App\Application\Components;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Phalcon\Di\Injectable;

/**
 * Helper class
 * Performs the additional Functionalties
 */
final class Helper extends Injectable
{
    /**
     * generateToken function
     *
     * Generates new token for the user who signups for the application
     */
    public function generateToken(string $name, string $email): string
    {
        $now = new DateTimeImmutable();
        $key = 'example_key';

        $payload = [
            'name' => $name,
            'email' => $email,
            'iss' => 'https://phalcon.io',
            'exp ' => $now->modify('+1 day')->getTimestamp(),
            'aud' => 'https://target.phalcon.io',
            'iat' => $now->getTimestamp(),
            'nbf' => $now->modify('-1 minute')->getTimestamp(),
            'password' => 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2'
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}
