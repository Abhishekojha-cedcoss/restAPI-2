<?php

declare(strict_types=1);

use MongoDB\BSON\ObjectID;
use Phalcon\Mvc\Controller;

final class AdminController extends Controller
{
    /**
     * listProductsAction function
     *
     * List the products and perform the delete and update products
     */
    public function listProductsAction(): void
    {
        $resultset = $this->mongo->products->find();
        $this->view->data = $resultset->toArray();
    }

    /**
     * listOrdersAction function
     *
     * List the Orders and perform the delete and update Orders
     */
    public function listOrdersAction(): void
    {
        if ($this->request->hasPost('submit')) {
            $status = $this->request->getPost('filter');
            $id = $this->request->getPost('id');
            $this->mongo->orders->updateOne(['_id' => new ObjectID($id)], ['$set' => ['status' => $status]]);
        }

        $resultset = $this->mongo->orders->find();
        $this->view->data = $resultset->toArray();
    }

    /**
     * addProductAction function
     *
     * Add a new Product to the database
     */
    public function addProductAction(): void
    {
        if ($this->request->hasPost('submit')) {
            $formdata = $this->request->getPost();
            $data = [
                'name' => $formdata['name'],
                'category' => $formdata['category'],
                'price' => $formdata['price'],
                'stock' => $formdata['stock'],
            ];
            try {
                $this->mongo->products->insertOne($data);
                $this->event->add();//Fire an event
                $this->view->message = 'Products added!!';
                $this->view->success = true;
            } catch (Exception $err) {
                die($err);
                $this->view->success = false;
                $this->view->message = 'There was some error!!';
            }
        }
    }
    /**
     * updateProductAction function
     *
     * Update the product details and fire an event
     */
    public function updateProductAction(): void
    {
        $id = $this->request->get('id');
        if ($this->request->hasPost('submit')) {
            $formdata = $this->request->getPost();
            $this->mongo->products->updateOne(['_id' => new ObjectID($id)], [
                '$set' => [
                    'name' => $formdata['name'],
                    'category' => $formdata['category'],
                    'price' => $formdata['price'],
                    'stock' => $formdata['stock'],
                ]
            ]);
            $this->event->update();//Fire an event
        }
        $this->view->data = (array) $this->mongo->products->findOne(['_id' => new ObjectID($id)]);
    }
}
