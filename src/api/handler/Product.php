<?php

namespace Api\Handler;

use Phalcon\Di\Injectable;

/**
 * Product Handler class
 * to handle all the product requests
 */
class Product extends Injectable
{
    /**
     * list function
     *To handle all the product list requests
     */
    public function list()
    {
        $response = $this->mongo->products->find()->toArray();
        $this->response->setStatusCode(200, 'Found');
        $this->response->setJsonContent($response);
        $this->response->send();
    }

    /**
     * get function
     *
     * @param integer $per_page
     * @param integer $page
     * @return void
     */
    public function get($per_page = 2, $page = 1)
    {
        $options = [
            "limit" => (int)$per_page,
            "skip"  => (int)(($page - 1) * $per_page)
        ];
        $array = [];
        $products =  $this->mongo->products->find([], $options);
        $products = $products->toArray();
        foreach ($products as $key => $value) {
            $id = (array)$value["_id"];
            $res = [
                "id" => $id["oid"],
                "name" => $value['name'],
            ];
            array_push($array, $res);
        }
        print_r($array);
    }

    /**
     * generateToken function
     *
     * Generate new Token
     * @return void
     */
    public function generateToken()
    {
    }
}
