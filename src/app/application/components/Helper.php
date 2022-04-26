<?php 
namespace App\Application\Components;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Phalcon\Di\Injectable;

/**
 * Helper class
 * Performs the additional Functionalties
 */
class Helper extends Injectable
{
    /**
     * generateToken function
     *
     * Generates new token for the user who signups for the application
     * @param [type] $name
     * @param [type] $email
     * @return void
     */
    public function generateToken($name, $email)
    {
        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $key = "example_key";

        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        $payload = array(
            "name" => $name,
            "email" => $email,
            "iss" => "'https://phalcon.io'",
            "exp " => $expires,
            "aud" => "https://target.phalcon.io",
            "iat" => $issued,
            "nbf" => $notBefore,
            "password" => $passphrase
        );

        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }

}