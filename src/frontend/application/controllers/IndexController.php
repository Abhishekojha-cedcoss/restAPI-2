<?php

declare(strict_types=1);

use MongoDB\BSON\ObjectID;
use Phalcon\Mvc\Controller;
use \Phalcon\Debug;

$debug = new Debug();

$debug->listen();

/**
 * IndexController class
 */
class IndexController extends Controller
{
    /**
     * indexAction function
     *
     * Displays the list of products
     */
    public function indexAction(): void
    {
        $products = $this->mongo->products->find()->toArray();
        $this->view->data = $products;
    }

    /**
     * updateAction function
     *
     * @return void
     */
    public function updateAction(): void
    {
        $response_data = json_decode($this->request->getPost("data"), true);
        $this->logger->info(json_encode($response_data));
        foreach ($response_data as $value) {
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

    /**
     * updateAction function
     *
     * @return void
     */
    public function addAction(): void
    {
        $data = json_decode($this->request->getPost("data"), true);
        $this->logger->info(json_encode($data));
        $this->mongo->products->insertOne(
            [
                "_id" => new ObjectID($data['_id']['$oid']),
                "name" => $data['name'],
                "category" => $data['category'],
                "price" => $data["price"],
                "stock" => $data['stock']
            ]
        );
    }
}
