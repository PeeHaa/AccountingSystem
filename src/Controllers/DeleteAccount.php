<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class DeleteAccount
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
        $deleteAccountRequest = $this->request->getParameter('id');

        if ($this->ledgerMapper->accountExists($deleteAccountRequest)) {
            $this->ledgerController->deleteAccount($deleteAccountRequest);
            $this->ledgerController->balanceAllAccounts();
        } else {
            $this->response->redirect('listAccounts.php');
        }

        $this->response->redirect('listAccounts.php');
    }
}