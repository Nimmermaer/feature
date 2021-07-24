<?php
declare(strict_types=1);

use Mblunck\Registration\Domain\Model\User;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

return [
    FrontendUser::class => [
        'subclasses' => [
            User::class,
        ],
    ],
    User::class => [
        'tableName' => 'fe_users',
        'recordType' => 'Tx_Registration_User',
        'properties' => [
            'birthday' => [
                'fieldname' => 'birthday',
            ]
        ]
    ],
];

