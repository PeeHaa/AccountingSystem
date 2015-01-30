<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;


class ChangePassword
{
    private $request;
    private $response;
    private $templateEngine;
    private $userMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, UserMapper $userMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->userMapper = $userMapper;
    }

    public function show()
    {
        if ($this->userMapper->isOnline()) {
            $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');
            $data['token'] = $_SESSION['CSRFToken'];

            $changePasswordRequest = $this->request->getParameter('changePassword');

            if (isset($changePasswordRequest)) {
                if ($this->request->getParameter('CSRFToken') == $_SESSION['CSRFToken']) {
                    $response = $this->userMapper->changePassword(
                        $this->request->getParameter('oldPassword'),
                        $this->request->getParameter('newPassword'),
                        $this->request->getParameter('newPasswordConfirmation')
                    );


                    if ($response == false) {
                        $data['errors'] = $this->userMapper->getErrors();
                    } else {
                        $data['messages'] = $this->userMapper->getMessages();
                    }
                } else {
                    $this->response->redirect('logout.php');
                }
            }

            $html = $this->templateEngine->render('ChangePassword', $data);

            $this->response->setContent($html);
        } else {
            $this->response->redirect('index.php');
        }
    }
}