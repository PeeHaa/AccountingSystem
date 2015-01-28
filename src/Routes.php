<?php
return [
	['GET', '/', ['AccountingSystem\Controllers\Homepage', 'show']],
    ['POST', '/', ['AccountingSystem\Controllers\Homepage', 'show']],

	['GET', '/index.php', ['AccountingSystem\Controllers\Homepage', 'show']],
    ['POST', '/index.php', ['AccountingSystem\Controllers\Homepage', 'show']],

    ['GET', '/logout.php', ['AccountingSystem\System\Mappers\UserMapper', 'logoutUser']],

    ['GET', '/changePassword.php', ['AccountingSystem\Controllers\ChangePassword', 'show']],
    ['POST', '/changePassword.php', ['AccountingSystem\Controllers\ChangePassword', 'show']],

    ['GET', '/createAccount.php', ['AccountingSystem\Controllers\Accounts', 'createAccount']],
    ['POST', '/createAccount.php', ['AccountingSystem\Controllers\Accounts', 'createAccount']],

    ['GET', '/trialBalance.php', ['AccountingSystem\Controllers\TrialBalance', 'show']],
    ['POST', '/trialBalance.php', ['AccountingSystem\Controllers\TrialBalance', 'show']],

    ['GET', '/listAccounts.php', ['AccountingSystem\Controllers\Accounts', 'listAccounts']],
    ['POST', '/listAccounts.php', ['AccountingSystem\Controllers\Accounts', 'listAccounts']],

    ['GET', '/editAccount.php', ['AccountingSystem\Controllers\Accounts', 'editAccount']],
    ['POST', '/editAccount.php', ['AccountingSystem\Controllers\Accounts', 'editAccount']],

    ['GET', '/viewAccount.php', ['AccountingSystem\Controllers\Accounts', 'viewAccount']],
    ['POST', '/viewAccount.php', ['AccountingSystem\Controllers\Accounts', 'viewAccount']],

    ['GET', '/deleteAccount.php', ['AccountingSystem\Controllers\Accounts', 'deleteAccount']],

    ['GET', '/createTransaction.php', ['AccountingSystem\Controllers\Transactions', 'createTransaction']],
    ['POST', '/createTransaction.php', ['AccountingSystem\Controllers\Transactions', 'createTransaction']],

    ['GET', '/deleteTransaction.php', ['AccountingSystem\Controllers\Transactions', 'deleteTransaction']],
    ['POST', '/deleteTransaction.php', ['AccountingSystem\Controllers\Transactions', 'deleteTransaction']],
];