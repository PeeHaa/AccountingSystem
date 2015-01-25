<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class CreateAccount
{
    private $request;
    private $response;
    private $templateEngine;
    private $ledgerController;
    private $ledgerMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, LedgerController $ledgerController, LedgerMapper $ledgerMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->ledgerController = $ledgerController;
        $this->ledgerMapper = $ledgerMapper;
    }

    public function show()
    {
        $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

        $createAccountRequest = $this->request->getParameter('createAccount');

        if (isset($createAccountRequest)) {
            $response = $this->ledgerController->createAccount(
                $this->request->getParameter('accountName'),
                $this->request->getParameter('accountClass')
            );

            if ($response == false) {
                $data['errors'] = $this->ledgerController->getErrors();
            } else {
                $data['messages'] = $this->ledgerController->getMessages();
            }
        }

        $html = $this->templateEngine->render('CreateAccount', $data);

        $this->response->setContent($html);
    }
}