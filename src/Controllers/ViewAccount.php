<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\TransactionsController as TransactionsController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class ViewAccount
{
    private $request;
    private $response;
    private $templateEngine;
    private $ledgerMapper;
    private $transactionsController;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, LedgerMapper $ledgerMapper, TransactionsController $transactionsController)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->ledgerMapper = $ledgerMapper;
        $this->transactionsController = $transactionsController;
    }

    public function show()
    {
        $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

        $accountLoadRequest = $this->request->getParameter('id');

        if (isset($accountLoadRequest) && $this->ledgerMapper->accountExists($accountLoadRequest)) {
            $transactionsData = $this->transactionsController->getTransactionsForAccount($accountLoadRequest);
            $data['accountName'] = $this->ledgerMapper->fetchAccountName($accountLoadRequest);
            $data['transactionsData'] = new \ArrayIterator($transactionsData);
        } else {
            $this->response->redirect('listAccounts.php');
        }

        $html = $this->templateEngine->render('ViewAccount', $data);
        $this->response->setContent($html);
    }
}