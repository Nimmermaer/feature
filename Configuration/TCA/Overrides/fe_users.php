<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Resource\File;
defined('TYPO3_MODE') || die();

if (!isset($GLOBALS['TCA']['fe_users']['ctrl']['type'])) {
    // no type field defined, so we define it here. This will only happen the first time the extension is installed!!
    $GLOBALS['TCA']['fe_users']['ctrl']['type'] = 'tx_extbase_type';
    $tempColumnsTx_registration_fe_users = [];
    $tempColumnsTx_registration_fe_users[$GLOBALS['TCA']['fe_users']['ctrl']['type']] = [
        'exclude' => true,
        'label' => 'LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration.tx_extbase_type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['', ''],
                ['User', 'Tx_Registration_User']
            ],
            'default' => 'Tx_Registration_User',
            'size' => 1,
            'maxitems' => 1,
        ]
    ];
    ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumnsTx_registration_fe_users);
}

ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    $GLOBALS['TCA']['fe_users']['ctrl']['type'],
    '',
    'after:' . $GLOBALS['TCA']['fe_users']['ctrl']['label']
);

$tmp_registration_columns = [

    'birthday' => [
        'exclude' => true,
        'l10n_mode' => 'exclude',
        'label' => 'LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_domain_model_user.birthday',
        'config' => [
            'dbType' => 'date',
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'size' => 7,
            'eval' => 'date,required',
            'default' => null,
        ],
    ],

];

ExtensionManagementUtility::addTCAcolumns('fe_users',$tmp_registration_columns);

// inherit and extend the show items from the parent class
if (isset($GLOBALS['TCA']['fe_users']['types']['0']['showitem'])) {
    $GLOBALS['TCA']['fe_users']['types']['Tx_Registration_User']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['0']['showitem'];
} elseif (is_array($GLOBALS['TCA']['fe_users']['types'])) {
    // use first entry in types array
    $fe_users_type_definition = reset($GLOBALS['TCA']['fe_users']['types']);
    $GLOBALS['TCA']['fe_users']['types']['Tx_Registration_User']['showitem'] = $fe_users_type_definition['showitem'];
} else {
    $GLOBALS['TCA']['fe_users']['types']['Tx_Registration_User']['showitem'] = '';
}
$GLOBALS['TCA']['fe_users']['types']['Tx_Registration_User']['showitem'] .= ',--div--;LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_domain_model_user,';
$GLOBALS['TCA']['fe_users']['types']['Tx_Registration_User']['showitem'] .= 'birthday';

$GLOBALS['TCA']['fe_users']['columns'][$GLOBALS['TCA']['fe_users']['ctrl']['type']]['config']['items'][] = [
    'LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_Registration_User',
    'Tx_Registration_User'
];
