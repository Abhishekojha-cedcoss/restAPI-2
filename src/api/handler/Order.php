<?php

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
     * @return void
     */
    public function place()
    {
        $getproduct = json_decode(json_encode($this->request->getJsonRawBody()), true);
        $product_id = ($getproduct['product_id']);
        $quantity = ($getproduct['quantity']);
        $order = [
            "customer name" => $GLOBALS["name"],
            "customer email" => $GLOBALS["email"],
            "product_id" => $product_id,
            "quantity" => $quantity,
            "status" => "paid",
            "date" => date("d/m/Y")
        ];
        $results = $this->mongo->orders->insertOne($order);

        $res = " Order id:" . $results->getInsertedId() . "";
        $this->response->setStatusCode(200, 'Found');
        $this->response->setJsonContent([
            "status" => "Order Placed Successfully!!",
            "data" => $res
        ]);
        $this->response->send();
    }

    /**
     * Undocumented function
     * Update the orders status
     * @return void
     */
    public function update()
    {
        $getproduct = json_decode(json_encode($this->request->getJsonRawBody()), true);
        $status = ($getproduct['status']);
        $order_id = ($getproduct['order_id']);
        $this->mongo->orders->updateOne(
            ["_id" => new ObjectID($order_id)],
            ['$set' => ["status" => ($status)]]
        );
        $this->response->setStatusCode(200, 'Order Updated');
        $this->response->setJsonContent([
            "status" => "Order Updated Successfully!!",
            "status new value" => $status
        ]);
        $this->response->send();
    }
}
