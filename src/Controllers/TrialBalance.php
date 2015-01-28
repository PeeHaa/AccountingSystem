<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Mappers\UserMapper as UserMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class TrialBalance
{
    private $request;
    private $response;
    private $templateEngine;
    private $ledgerController;
    private $userMapper;

    public function __construct(Request $request, Response $response, TemplateEngine $templateEngine, LedgerController $ledgerController, UserMapper $userMapper)
    {
        $this->request = $request;
        $this->response = $response;
        $this->templateEngine = $templateEngine;
        $this->ledgerController = $ledgerController;
        $this->userMapper = $userMapper;
    }

    public function show()
    {
        if ($this->userMapper->isOnline()) {
            $data = require_once(rtrim(__DIR__, 'Controllers') . 'TemplateParameters.php');

            $this->ledgerController->balanceAllAccounts();
            $accountNamesAndBalances = $this->ledgerController->getAccountNamesAndBalances();
            $balanceTotals = $this->ledgerController->getAccountBalanceTotals();

            $data['debitTotal'] = $balanceTotals['debit'];
            $data['creditTotal'] = $balanceTotals['credit'];

            $data['account'] = new \ArrayIterator($accountNamesAndBalances);

            $html = $this->templateEngine->render('TrialBalance', $data);
            $this->response->setContent($html);
        } else {
            $this->response->redirect('index.php');
        }
    }
} 