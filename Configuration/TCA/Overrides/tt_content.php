<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
defined('TYPO3_MODE') || die();

ExtensionUtility::registerPlugin(
    'Registration',
    'Subscribe',
    'Subscribe'
);

ExtensionUtility::registerPlugin(
    'Registration',
    'Edit',
    'Edit'
);

ExtensionUtility::registerPlugin(
    'Registration',
    'Login',
    'Login'
);
