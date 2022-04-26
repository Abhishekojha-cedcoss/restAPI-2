<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use App\Application\Components\Listener;

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

    /**
     * addProductAction function
     *
     * Add a new Product to the database
     * @return void
     */
    public function addProductAction()
    {
        if ($this->request->hasPost("submit")) {
            $formdata = $this->request->getPost();
            $data = [
                "name" => $formdata["name"],
                "category" => $formdata["category"],
                "price" => $formdata["price"],
                "stock" => $formdata["stock"],
            ];
            try {
                $this->mongo->products->insertOne($data);

                //........................................<Event Fired>...........................................//
                $eventsManager = new EventsManager();
                $component   = new App\Application\Components\Loader();

                $component->setEventsManager($eventsManager);
                $eventsManager->attach(
                    'notifications',
                    new Listener()
                );
                $component->add();
                //........................................<Event Fired>...........................................//

                $this->view->message = "Products added!!";
                $this->view->success = true;
            } catch (Exception $e) {
                die($e);
                $this->view->success = false;
                $this->view->message = "There was some error!!";
            }
        }
    }
    public function updateProductAction()
    {
        $id = $this->request->get("id");
        if ($this->request->hasPost("submit")) {
            $formdata = $this->request->getPost();
            $data = [
                "name" => $formdata["name"],
                "category" => $formdata["category"],
                "price" => $formdata["price"],
                "stock" => $formdata["stock"],
            ];
            $this->mongo->products->updateOne(["_id" => new ObjectID($id)], ['$set' => $data]);

            //........................................<Event Fired>...........................................//
            $eventsManager = new EventsManager();
            $component   = new App\Application\Components\Loader();

            $component->setEventsManager($eventsManager);
            $eventsManager->attach(
                'notifications',
                new Listener()
            );
            $component->update();
            //........................................<Event Fired>...........................................//
        }

        $this->view->data = (array)$this->mongo->products->findOne(["_id" => new ObjectID($id)]);
    }
}
