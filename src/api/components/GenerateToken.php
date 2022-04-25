<?php

namespace Api\Components;

use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;
use DateTimeImmutable;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use MongoDB\BSON\ObjectID;

$email = '';
$name = '';
/**
 * GenerateToken class
 * Generate the new token for differen user
 */
class GenerateToken implements MiddlewareInterface
{
    public function authorizeApiToken($app)
    {
        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $key = "example_key";

        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        $payload = array(
            "name" => "abhishek",
            "iss" => "'https://phalcon.io'",
            "exp " => $expires,
            "aud" => "https://target.phalcon.io",
            "iat" => $issued,
            "nbf" => $notBefore,
            "password" => $passphrase
        );

        $token = JWT::encode($payload, $key, 'HS256');
        $app->response->setStatusCode(400)
            ->setJsonContent($token)
            ->send();
    }

    /**
     * validate function
     *
     * Validate the token provided by the user
     * @param [type] $token
     * @param [type] $app
     * @return void
     */
    public function validate($token, $app)
    {
        $key = "example_key";
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $GLOBALS["email"] = $decoded->email;
        $GLOBALS["name"] = $decoded->name;
    }
    public function call(Micro $app)
    {
        $check = explode('/', $app->request->get()['_url'])[1];
        $check1 = explode('/', $app->request->get()['_url'])[2];
        if ($check == "/api/generateApiToken") {
            $this->authorizeApiToken($app);
        } elseif ($check1 == "placeorder") {
            $response = new Response();
            $request = new Request();
            $getproduct = json_decode(json_encode($request->getJsonRawBody()), true);
            $checkproduct_id = ($getproduct['product_id']);
            try {
                $result = $app->mongo->products->findOne([
                    "_id" => new ObjectID($checkproduct_id)
                ]);
                if (!empty($result)) {
                    if ($getproduct['quantity'] < 0 || $getproduct['quantity'] > $result->stock) {
                        if ($getproduct['quantity'] < 0) {
                            $response->setStatusCode(404, 'Not Available');
                            $response->setJsonContent("Quantity Can't be Negative.");
                        } else {
                            $response->setStatusCode(404, 'Not Available');
                            $response->setJsonContent("This much Quantity not available for this Product!!'");
                        }
                        $response->send();
                        die;
                    }
                    $token =  $app->request->get("token");
                    $this->validate($token, $app);
                } else {
                    $response->setStatusCode(404, 'No Match Found ');
                    $response->setJsonContent("Invalid Product ID");
                    $response->send();
                    die;
                }
            } catch (\Exception $e) {
                $response->setStatusCode(404, 'Please Enter Valid Product ID');
                $response->setJsonContent("Please Enter Valid Product ID!!");
                $response->send();
                die;
            }
        } else {
            $token =  $app->request->get("token");
            $this->validate($token, $app);
        }
    }
}
