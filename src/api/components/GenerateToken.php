<?php

declare(strict_types=1);

namespace Api\Components;

use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;
use DateTimeImmutable;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

/**
 * GenerateToken class
 * Generate the new token for differen user
 */
final class GenerateToken implements MiddlewareInterface
{
    public function authorizeApiToken($app): void
    {
        $now = new DateTimeImmutable();
        $key = 'example_key';

        $payload = array(
            'name' => 'abhishek',
            'iss' => 'https://phalcon.io',
            'exp ' => $now->modify('+1 day')->getTimestamp(),
            'aud' => 'https://target.phalcon.io',
            'iat' => $now->getTimestamp(),
            'nbf' => $now->modify('-1 minute')->getTimestamp(),
            'password' => 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2'
        );
        $app->response->setStatusCode(400)
            ->setJsonContent(JWT::encode($payload, $key, 'HS256'))
            ->send();
    }

    /**
     * validate function
     *
     * Validate the token provided by the user
     *
     * @param string $token
     * @param object $app
     */
    public function validate($token, $app): void
    {
        $key = 'example_key';
        if ($token === null) { //Checks if the token is present or not
            $app->response->setStatusCode(401, 'Authorization failed!! Token not Found')
                ->setJsonContent('Authorization failed!! Token not Found')
                ->send();
            die;
        }

        try { //Checks if the email and name are present in the token or not
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $GLOBALS['email'] = $decoded->email;
            $GLOBALS['name'] = $decoded->name;
        } catch (\Exception $err) { //If the email or name are not given then an exception is thrown
            $app->response->setStatusCode(400)
                ->setJsonContent($err->getMessage())
                ->send();
            die;
        }
    }
    public function call(Micro $app): void
    {
        $check = explode('/', $app->request->get()['_url']);
        if ($check[1] === 'generatetoken') {
            $this->authorizeApiToken($app);
        } else {
            $token = $app->request->get('token');
            $this->validate($token, $app);
        }
    }
}
