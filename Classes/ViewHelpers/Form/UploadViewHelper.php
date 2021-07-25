<?php


namespace Mblunck\Registration\ViewHelpers\Form;


use Mblunck\Registration\Domain\Model\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Property\Exception;
use TYPO3\CMS\Extbase\Property\PropertyMapper;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

/**
 * Class UploadViewHelper
 * @package Mblunck\Registration\ViewHelpers\Form
 */
class UploadViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\UploadViewHelper
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\Inject
     * @var HashService | null
     */
    protected ?HashService $hashService = null;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Inject
     * @var PropertyMapper| null
     */
    protected ?PropertyMapper $propertyMapper = null;

    public function __construct()
    {
        parent::__construct();
        $this->propertyMapper = (GeneralUtility::makeInstance(PropertyMapper::class) instanceof PropertyMapper) ?
            GeneralUtility::makeInstance(PropertyMapper::class) :
            null;
        $this->hashService =   $this->userRepository = (GeneralUtility::makeInstance(HashService::class) instanceof HashService) ?
            GeneralUtility::makeInstance(HashService::class) :
            null;
    }

    /**
     * Render the upload field including possible resource pointer
     *
     * @return string
     * @throws Exception
     * @api
     */
    public function render(): string
    {
        $output = '';

        $resource = $this->getUploadedResource();
        if ($resource !== null) {
            $resourcePointerIdAttribute = '';
            if ($this->hasArgument('id')) {
                $resourcePointerIdAttribute = ' id="' . htmlspecialchars($this->arguments['id']) . '-file-reference"';
            }
            $resourcePointerValue = $resource->getUid();
            if ($resourcePointerValue === null) {
                // Newly created file reference which is not persisted yet.
                // Use the file UID instead, but prefix it with "file:" to communicate this to the type converter
                $resourcePointerValue = 'file:' . $resource->getOriginalResource()->getOriginalFile()->getUid();
            }
            $output .= '<input type="hidden" name="' . $this->getName() . '[submittedFile][resourcePointer]" value="' . htmlspecialchars($this->hashService->appendHmac((string)$resourcePointerValue)) . '"' . $resourcePointerIdAttribute . ' />';

            $this->templateVariableContainer->add('resource', $resource);
            $output .= $this->renderChildren();
            $this->templateVariableContainer->remove('resource');
        }

        $output .= parent::render();
        return $output;
    }


    /**
     * Return a previously uploaded resource.
     * Return NULL if errors occurred during property mapping for this property.
     * @return FileReference|mixed|Error|null
     * @throws Exception
     */
    protected function getUploadedResource(): ?FileReference
    {
        $resource = null;
        if ($this->getMappingResultsForProperty()->hasErrors()) {
            return null;
        }
        if (is_callable(function () {
            return $this->getValueAttribute();
        })) {
            $resource = $this->getValueAttribute();
        }
        if ($resource instanceof FileReference) {
            return $resource;
        }
        return $this->propertyMapper->convert($resource, FileReference::class);
    }

}
