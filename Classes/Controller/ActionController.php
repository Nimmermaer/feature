<?php


namespace Mblunck\Registration\Controller;


use Mblunck\Registration\Domain\Model\FileReference;
use Mblunck\Registration\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\Container\Container;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

/**
 * Class ActionController
 * @package Mblunck\Registration\Controller
 */
class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @param ViewInterface $view
     */
    public function initializeView(ViewInterface $view): void
    {
        $view->assign('data', $this->configurationManager->getContentObject()->data);
        parent::initializeView($view);
    }

    /**
     * @param string $argumentName
     */
    protected function setTypeConverterConfigurationForImageUpload(string $argumentName): void
    {
        GeneralUtility::makeInstance(Container::class)
            ->registerImplementation(
                \TYPO3\CMS\Extbase\Domain\Model\FileReference::class,
                FileReference::class
            );


        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/content/',
        ];
        $propertyMappingConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();

        $propertyMappingConfiguration->forProperty('image.0')
            ->setTypeConverterOptions(
                UploadedFileReferenceConverter::class,
                $uploadConfiguration
            );
    }
}
