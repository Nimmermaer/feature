<?php
declare(strict_types=1);

return [
    \TYPO3\CMS\Extbase\Domain\Model\FrontendUser::class => [
        'subclasses' => [
            \Mblunck\Registration\Domain\Model\User::class,
        ],
    ],
    \Mblunck\Registration\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'recordType' => 'Tx_Registration_User',
        'properties' => [
            'birthday' => [
                'fieldname' => 'birthday',
            ]
        ]
    ],
];

