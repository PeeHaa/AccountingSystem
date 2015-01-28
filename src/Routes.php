<?php
return [
	['GET', '/', ['AccountingSystem\Controllers\Homepage', 'show']],
    ['POST', '/', ['AccountingSystem\Controllers\Homepage', 'show']],

	['GET', '/index.php', ['AccountingSystem\Controllers\Homepage', 'show']],
    ['POST', '/index.php', ['AccountingSystem\Controllers\Homepage', 'show']],

    ['GET', '/logout.php', ['AccountingSystem\System\Mappers\UserMapper', 'logoutUser']],

    ['GET', '/changePassword.php', ['AccountingSystem\Controllers\ChangePassword', 'show']],
    ['POST', '/changePassword.php', ['AccountingSystem\Controllers\ChangePassword', 'show']],

    ['GET', '/createAccount.php', ['AccountingSystem\Controllers\CreateAccount', 'show']],
    ['POST', '/createAccount.php', ['AccountingSystem\Controllers\CreateAccount', 'show']],

    ['GET', '/trialBalance.php', ['AccountingSystem\Controllers\TrialBalance', 'show']],
    ['POST', '/trialBalance.php', ['AccountingSystem\Controllers\TrialBalance', 'show']],

    ['GET', '/listAccounts.php', ['AccountingSystem\Controllers\ListAccounts', 'show']],
    ['POST', '/listAccounts.php', ['AccountingSystem\Controllers\ListAccounts', 'show']],

    ['GET', '/editAccount.php', ['AccountingSystem\Controllers\EditAccount', 'show']],
    ['POST', '/editAccount.php', ['AccountingSystem\Controllers\EditAccount', 'show']],

    ['GET', '/viewAccount.php', ['AccountingSystem\Controllers\ViewAccount', 'show']],
    ['POST', '/viewAccount.php', ['AccountingSystem\Controllers\ViewAccount', 'show']],

    ['GET', '/deleteAccount.php', ['AccountingSystem\Controllers\DeleteAccount', 'show']],

    ['GET', '/createTransaction.php', ['AccountingSystem\Controllers\Transactions', 'createTransaction']],
    ['POST', '/createTransaction.php', ['AccountingSystem\Controllers\Transactions', 'createTransaction']],

    ['GET', '/deleteTransaction.php', ['AccountingSystem\Controllers\Transactions', 'deleteTransaction']],
    ['POST', '/deleteTransaction.php', ['AccountingSystem\Controllers\Transactions', 'deleteTransaction']],
];