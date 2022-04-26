<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;

/**
 * IndexController class
 */
class IndexController extends Controller
{
    /**
     * indexAction function
     *
     * redirects to the login page
     * @return void
     */
    public function indexAction()
    {
        $products = $this->mongo->products->find()->toArray();
        $this->view->data = $products;
    }

    /**
     * updateAction function
     *
     * @return void
     */
    public function updateAction()
    {
        $response_data = json_decode($this->request->getPost("data"), true);
        foreach ($response_data as $key => $value) {
            $this->mongo->products->updateOne(
                ["_id" => new ObjectID($value['_id']['$oid'])],
                [
                    '$set' => [
                        "name" => $value['name'],
                        "category" => $value['category'],
                        "price" => $value["price"],
                        "stock" => $value['stock']
                    ]
                ]
            );
        }
    }
}
