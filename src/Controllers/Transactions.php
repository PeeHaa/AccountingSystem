<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Controllers\TransactionsController as TransactionsController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class Transactions
{
    private $request;
    private $response;
    private $templateEngine;
    private $transactionsController;
    private $ledgerController;
    private $ledgerMapper;
    private $userMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, TransactionsController $transactionsController, LedgerController $ledgerController, LedgerMapper $ledgerMapper, UserMapper $userMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->transactionsController = $transactionsController;
        $this->ledgerController = $ledgerController;
        $this->ledgerMapper = $ledgerMapper;
        $this->userMapper = $userMapper;
    }

    public function createTransaction()
    {
        if ($this->userMapper->isOnline()) {
            $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

            $createTransactionRequest = $this->request->getParameter('createTransaction');

            $accountData = $this->ledgerController->getAccountNamesAndIDs();

            $data['accounts'] = new \ArrayIterator($accountData);

            if (isset($createTransactionRequest)) {
                $result = $this->transactionsController->createTransaction(
                    $this->request->getParameter('debitAccountID'),
                    $this->request->getParameter('creditAccountID'),
                    $this->request->getParameter('debitDescription'),
                    $this->request->getParameter('creditDescription'),
                    $this->request->getParameter('amount')
                );

                if ($result == false) {
                    $data['errors'] = $this->transactionsController->getErrors();
                } else {
                    $data['messages'] = $this->transactionsController->getMessages();
                }
            }

            $html = $this->templateEngine->render('CreateTransaction', $data);
            $this->response->setContent($html);
        } else {
            $this->response->redirect('index.php');
        }
    }

    public function deleteTransaction()
    {
        if ($this->userMapper->isOnline()) {
            $deleteTransactionRequest = $this->request->getParameter('id');

            if ($this->ledgerMapper->accountExists($deleteTransactionRequest)) {
                $this->transactionsController->deleteTransaction($deleteTransactionRequest);
            }
            $this->response->redirect('listAccounts.php');
        } else {
            $this->response->redirect('index.php');
        }
    }
}