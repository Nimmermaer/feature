<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Mblunck\Registration\Controller\UserController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    ExtensionUtility::configurePlugin(
        'Registration',
        'Subscribe',
        [
            UserController::class => 'subscribe'
        ],
        // non-cacheable actions
        [
            UserController::class => 'subscribe'
        ]
    );

    ExtensionUtility::configurePlugin(
        'Registration',
        'Edit',
        [
            UserController::class => 'edit'
        ],
        // non-cacheable actions
        [
            UserController::class => 'edit'
        ]
    );

    // wizards
    ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    subscribe {
                        iconIdentifier = registration-plugin-subscribe
                        title = LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_subscribe.name
                        description = LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_subscribe.description
                        tt_content_defValues {
                            CType = list
                            list_type = registration_subscribe
                        }
                    }
                    edit {
                        iconIdentifier = registration-plugin-edit
                        title = LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_edit.name
                        description = LLL:EXT:registration/Resources/Private/Language/locallang_db.xlf:tx_registration_edit.description
                        tt_content_defValues {
                            CType = list
                            list_type = registration_edit
                        }
                    }
                }
                show = *
            }
       }'
    );

    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'registration-plugin-subscribe',
        SvgIconProvider::class,
        ['source' => 'EXT:registration/Resources/Public/Icons/user_plugin_subscribe.svg']
    );
    $iconRegistry->registerIcon(
        'registration-plugin-edit',
        SvgIconProvider::class,
        ['source' => 'EXT:registration/Resources/Public/Icons/user_plugin_edit.svg']
    );
});
