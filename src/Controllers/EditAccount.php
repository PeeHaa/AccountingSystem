<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class EditAccount
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

        $idRequest = $this->request->getParameter('id');
        $updateRequest = $this->request->getParameter('updateAccount');

        if (!empty($idRequest) && $this->ledgerMapper->accountExists($idRequest)) {
            $html = $this->templateEngine->render('EditAccount', $data);
        } else {
            $this->response->redirect("listAccounts.php");
            return false;
        }

        if (isset($updateRequest)) {
            $result = $this->ledgerController->editAccount(
                $this->request->getParameter('id'),
                $this->request->getParameter('accountName'),
                $this->request->getParameter('accountClass')
            );

            if ($result == false) {
                $data['errors'] = $this->ledgerController->getErrors();
            } else {
                $data['messages'] = $this->ledgerController->getMessages();
            }

            $html = $this->templateEngine->render('EditAccount', $data);
        }

        $this->response->setContent($html);
    }
} 