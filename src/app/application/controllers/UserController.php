<?php

use App\Application\Components\Helper;
use App\Application\Components\EscapeClass;
use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    /**
     * Signup Function
     * Registers new user with the email and password in the database
     * 
     * @return void
     */
    public function signupAction()
    {
        $helper = new Helper();
        if ($this->request->hasPost("submit")) {
            $formdata = $this->request->getPost();
            $token = $helper->generateToken($formdata["name"], $formdata["email"]);
            $array = [
                "name" => $formdata["name"],
                "email" => $formdata["email"],
                "password" => $formdata["password"],
                "token" => $token,
                "role" => "user"
            ];
            try {
                $this->mongo->users->insertOne($array);
                $this->view->success = true;
                $this->view->message = "Registration Complete!! <br> Please copy the below token: <br>" . $token;
            } catch (Exception $e) {
                $this->view->success = true;
                $this->view->message = "Something went wrong! Please try after sometime!";
            }
        }
    }

    /**
     * loginAction function
     * Makes a user login if the details provided by him are correct.
     * @return void
     */
    public function loginAction()
    {
        $helper = new Helper();
        $escaper = new EscapeClass();
        if ($this->request->hasPost("submit")) {
            $email = $escaper->sanitize($this->request->getPost("email"));
            $password = $escaper->sanitize($this->request->getPost("password"));
            $data = $this->mongo->users->findOne(["email" => $email, "password" => $password]);
            $data = (array)$data;
            if (count($data) > 0) {
                if ($data["role"] == "admin") {
                    $this->response->redirect(URLROOT."/app/admin/listProducts");
                } elseif ($data["role"] == "user") {
                    $this->response->redirect(URLROOT."/app/user/createWebHook");
                }
            } else {
                $this->view->success = false;
                $this->view->message = "Wrong Credentials!!";
                $this->logger->error("Wrong Credentials!!");
            }
        }
    }

    /**
     * Signout function
     *
     * @return void
     */
    public function signoutAction()
    {
        $this->response->redirect(URLROOT."/app/user/login");
    }

    /**
     * createWebHookAction function
     *
     * Form to create webHook for user
     * @return void
     */
    public function createWebHookAction()
    {
        if ($this->request->hasPost("submit")) {
            $data = $this->request->getPost();
            $this->mongo->webhooks->insertOne(["name" => $data["name"], "url" => $data["url"], "key" => $data["key"], "event" => $data["event"]]);
        }
    }
}
