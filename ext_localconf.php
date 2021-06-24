<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Registration',
        'Subscribe',
        [
            \Mblunck\Registration\Controller\UserController::class => 'subscribe'
        ],
        // non-cacheable actions
        [
            \Mblunck\Registration\Controller\UserController::class => 'subscribe'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Registration',
        'Edit',
        [
            \Mblunck\Registration\Controller\UserController::class => 'edit'
        ],
        // non-cacheable actions
        [
            \Mblunck\Registration\Controller\UserController::class => 'edit'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
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

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'registration-plugin-subscribe',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:registration/Resources/Public/Icons/user_plugin_subscribe.svg']
    );
    $iconRegistry->registerIcon(
        'registration-plugin-edit',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:registration/Resources/Public/Icons/user_plugin_edit.svg']
    );
});
