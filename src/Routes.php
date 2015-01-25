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
];