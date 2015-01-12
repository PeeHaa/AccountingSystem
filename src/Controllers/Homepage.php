<?php
namespace AccountingSystem\Controllers;

use Http\Request;
use Http\Response;
use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\System\Controllers\UserController as UserController;
use AccountingSystem\Template\Engine as TemplateEngine;

class Homepage
{
    private $request;
    private $response;
    private $templateEngine;
    private $userController;
    private $userMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, UserController $userController, UserMapper $userMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->userController = $userController;
        $this->userMapper = $userMapper;
    }

    public function show()
    {
        $loginRequest = $this->request->getParameter('login');
        $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

        if($this->userMapper->isOnline()) {
            $html = $this->templateEngine->render('SystemHomepage', $data);
        } else {
            $html = $this->templateEngine->render('Homepage', $data);
        }

        if(isset($loginRequest)) {
            if($this->userController->login($this->request->getParameter('username'), $this->request->getParameter('password'))) {
                $html = $this->templateEngine->render('SystemHomepage', $data);
            } else {
                $html = $this->templateEngine->render('Homepage', $data);
            }
        }
        
        $this->response->setContent($html);
    }
}