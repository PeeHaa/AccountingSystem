<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class Users
{
    private $userMapper;
    private $templateEngine;
    private $request;
    private $response;

    public function __construct(UserMapper $userMapper, TemplateEngine $templateEngine, Request $request, Response $response)
    {
        $this->userMapper = $userMapper;
        $this->templateEngine = $templateEngine;
        $this->request = $request;
        $this->response = $response;
    }

    public function newUser()
    {
        $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

        $newUserRequest = $this->request->getParameter('createUser');

        $data['token'] = $_SESSION['CSRFToken'];

        if ($this->userMapper->isOnline() == false) {
            $this->response->redirect('index.php');
        }

        if (isset($newUserRequest)) {
            $username = $this->request->getParameter('username');
            $password = $this->request->getParameter('password');
            $rank = $this->request->getParameter('rank');

            if ($this->userMapper->isAnAdmin($_SESSION['username']) == false) {
                $this->response->redirect('index.php');
            }
            if ($this->userMapper->isValidRegistrationDetails($username, $password, $rank)) {
                $this->userMapper->register($username, $password, $rank);
            } else {
                $data['errors'] = $this->userMapper->getErrors();
            }
        }

        $html = $this->templateEngine->render('newUser', $data);
        $this->response->setContent($html);
    }
}