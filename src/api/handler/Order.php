<?php

declare(strict_types=1);

namespace Api\Handler;

use Phalcon\Di\Injectable;
use MongoDB\BSON\ObjectID;

/**
 * Product Handler class
 * to handle all the product requests
 */
class Order extends Injectable
{
    /**
     * place function
     * Place new order api
     */
    public function place(): void
    {
        $getproduct = json_decode(json_encode($this->request->getJsonRawBody()), true);
        $id = $getproduct["product_id"];
        print_r($id);
        die;
        $order = [
            'customer name' => $GLOBALS['name'],
            'customer email' => $GLOBALS['email'],
            'product_id' => $getproduct['product_id'],
            'quantity' => $getproduct['quantity'],
            'status' => 'paid',
            'date' => date('d/m/Y')
        ];
        $results = $this->mongo->orders->insertOne($order);

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
            ['$set' => ['status' => $getproduct['status']]]
        );
        $this->response->setStatusCode(200, 'Order Updated');
        $this->response->setJsonContent([
            'status' => 'Order Updated Successfully!!',
            'status new value' => $getproduct['status']
        ]);
        $this->response->send();
    }
}
