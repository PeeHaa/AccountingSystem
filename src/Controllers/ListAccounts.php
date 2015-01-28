<?php
namespace AccountingSystem\Controllers;

use AccountingSystem\System\Controllers\LedgerController as LedgerController;
use AccountingSystem\System\Mappers\LedgerMapper as LedgerMapper;
use AccountingSystem\Template\Engine as TemplateEngine;
use Http\Request;
use Http\Response;

class ListAccounts
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

        $namesAndIDs = $this->ledgerController->getAccountNamesAndIDs();

        $data['accountData'] = new \ArrayIterator($namesAndIDs);

        $html = $this->templateEngine->render('ListAccounts', $data);
        $this->response->setContent($html);
        return true;
    }
} 