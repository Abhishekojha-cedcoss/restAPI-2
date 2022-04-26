<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

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
        $options = ["typeMap" => ['root' => 'array', 'document' => 'array']];
        $result = $this->mongo->products->find([], $options)->toArray();

        $this->view->data = $result;
    }

    /**
     * updateAction function
     *
     * @return void
     */
    public function updateAction()
    {
        $this->logger->info("Hello");
        $data = json_decode($this->request->getPost("data"), true);
        foreach ($data as $key => $value) {
            $id = (array)$value['_id'];
            $this->logger->info($id);
        }
    }
}
