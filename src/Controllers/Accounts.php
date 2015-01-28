<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Controllers\TransactionsController as TransactionsController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class Accounts
{
    private $request;
    private $response;
    private $templateEngine;
    private $ledgerController;
    private $ledgerMapper;
    private $transactionsController;
    private $userMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, LedgerController $ledgerController, LedgerMapper $ledgerMapper, TransactionsController $transactionsController, UserMapper $userMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->ledgerController = $ledgerController;
        $this->ledgerMapper = $ledgerMapper;
        $this->transactionsController = $transactionsController;
        $this->userMapper = $userMapper;
    }

    public function createAccount()
    {
        if ($this->userMapper->isOnline()) {
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
        } else {
            $this->response->redirect('index.php');
        }
    }

    public function viewAccount()
    {
        if ($this->userMapper->isOnline()) {
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
        } else {
            $this->response->redirect('index.php');
        }
    }

    public function listAccounts()
    {
        if ($this->userMapper->isOnline()) {
            $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

            $namesAndIDs = $this->ledgerController->getAccountNamesAndIDs();

            $data['accountData'] = new \ArrayIterator($namesAndIDs);

            $html = $this->templateEngine->render('ListAccounts', $data);
            $this->response->setContent($html);
            return true;
        } else {
            $this->response->redirect('index.php');
        }
    }

    public function editAccount()
    {
        if ($this->userMapper->isOnline()) {
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
        } else {
            $this->response->redirect('index.php');
        }
    }

    public function deleteAccount()
    {
        if ($this->userMapper->isOnline()) {
            $deleteAccountRequest = $this->request->getParameter('id');

            if ($this->ledgerMapper->accountExists($deleteAccountRequest)) {
                $this->ledgerController->deleteAccount($deleteAccountRequest);
                $this->ledgerController->balanceAllAccounts();
            } else {
                $this->response->redirect('listAccounts.php');
            }

            $this->response->redirect('listAccounts.php');
        } else {
            $this->response->redirect('index.php');
        }
    }
}