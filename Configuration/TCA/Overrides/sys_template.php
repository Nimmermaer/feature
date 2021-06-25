<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3_MODE') || die();

ExtensionManagementUtility::addStaticFile('registration', 'Configuration/TypoScript', 'registration');
