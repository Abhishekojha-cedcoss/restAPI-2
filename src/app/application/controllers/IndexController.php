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
        $this->response->redirect("http://localhost:8080/app/user/login");
    }
}