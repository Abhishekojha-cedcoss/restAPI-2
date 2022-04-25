<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;

class AdminController extends Controller
{
    /**
     * listProductsAction function
     *
     * List the products and perform the delete and update products
     *
     * @return void
     */
    public function listProductsAction()
    {
        $resultset = $this->mongo->products->find();
        $this->view->data = $resultset->toArray();
    }

        /**
     * listOrdersAction function
     *
     * List the Orders and perform the delete and update Orders
     *
     * @return void
     */
    public function listOrdersAction()
    {
        if ($this->request->hasPost("submit")) {
            $status = $this->request->getPost("filter");
            $id = $this->request->getPost("id");
            $this->mongo->orders->updateOne(["_id" => new ObjectID($id)], ['$set' => ["status" => $status]]);
        }

        $resultset = $this->mongo->orders->find();
        $this->view->data = $resultset->toArray();
    }
}
