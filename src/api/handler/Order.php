<?php

declare(strict_types=1);

namespace Api\Handler;

use Phalcon\Di\Injectable;
use MongoDB\BSON\ObjectID;

/**
 * Product Handler class
 * to handle all the product requests
 */
final class Order extends Injectable
{
    /**
     * place function
     * Place new order api
     */
    public function place(): void
    {
        $getproduct = json_decode(json_encode($this->request->getJsonRawBody()), true);
        try {
            $this->mongo->products->findOne([
                "_id" => new ObjectID($getproduct['product_id'])
            ]);
        } catch (\Exception $e) {
            $this->response->setStatusCode(404, 'Please Enter Valid Product ID');
            $this->response->setJsonContent("Please Enter Valid Product ID!!");
            $this->response->send();
            die;
        }
        $results = $this->mongo->orders->insertOne($this->createOrderArray($getproduct));

        $this->response->setStatusCode(200, 'Found');
        $this->response->setJsonContent([
            'status' => 'Order Placed Successfully!!',
            'data' => 'Order id:' . $results->getInsertedId() . ''
        ]);
        $this->response->send();
    }

    /**
     * Undocumented function
     * Update the orders status
     */
    public function update(): void
    {
        $getproduct = json_decode(json_encode($this->request->getJsonRawBody()), true);
        $this->mongo->orders->updateOne(
            ['_id' => new ObjectID($getproduct['status'])],
            [
                '$set' => [
                    'status' => $getproduct['status']
                ]
            ]
        );
        $this->response->setStatusCode(200, 'Order Updated');
        $this->response->setJsonContent([
            'status' => 'Order Updated Successfully!!',
            'status new value' => $getproduct['status']
        ]);
        $this->response->send();
    }

    /**
     * createOrderArray function
     * Creates the order array to place the order
     */
    public function createOrderArray($data): array
    {
        $order = [
            'customer name' => $GLOBALS['name'],
            'customer email' => $GLOBALS['email'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'status' => 'paid',
            'date' => date('d/m/Y')
        ];
        return $order;
    }
}
