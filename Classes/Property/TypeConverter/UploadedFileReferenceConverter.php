<?php


namespace Mblunck\Registration\Property\TypeConverter;


use Exception;
use InvalidArgumentException;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\File as FalFile;
use TYPO3\CMS\Core\Resource\FileReference as FalFileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Property\Exception\TypeConverterException;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;
use const UPLOAD_ERR_FORM_SIZE;
use const UPLOAD_ERR_INI_SIZE;
use const UPLOAD_ERR_NO_FILE;
use const UPLOAD_ERR_OK;
use const UPLOAD_ERR_PARTIAL;

/**
 * Class UploadedFileReferenceConverter
 * @package Mblunck\Registration\Property\TypeConverter
 */
class UploadedFileReferenceConverter extends AbstractTypeConverter
{

    /**
     * Folder where the file upload should go to (including storage).
     */
    const CONFIGURATION_UPLOAD_FOLDER = 1;

    /**
     * How to handle a upload when the name of the uploaded file conflicts.
     */
    const CONFIGURATION_UPLOAD_CONFLICT_MODE = 2;

    /**
     * Whether to replace an already present resource.
     * Useful for "maxitems = 1" fields and properties
     * with no ObjectStorage annotation.
     */
    const CONFIGURATION_ALLOWED_FILE_EXTENSIONS = 4;

    /**
     * @var string
     */
    protected string $defaultUploadFolder = '1:/user_upload/';

    /**
     * @var string[]
     */
    protected array $sourceTypes = ['array'];

    /**
     * @var string
     */
    protected string $targetType = FileReference::class;

    /**
     * Take precedence over the available FileReferenceConverter
     *
     * @var int
     */
    protected int $priority = 30;

    /**
     * @var ResourceFactory|null
     */
    protected ?ResourceFactory $resourceFactory = null;

    /**
     * @var HashService|null
     */
    protected ?HashService $hashService = null;

    /**
     * @var PersistenceManager|null
     */
    protected ?PersistenceManager $persistenceManager = null;

    /**
     * @var object[]
     */
    protected array $convertedResources = [];

    public function __construct()
    {
        $this->resourceFactory = (GeneralUtility::makeInstance(ResourceFactory::class) instanceof ResourceFactory) ?
            GeneralUtility::makeInstance(ResourceFactory::class) :
            null;

        $this->hashService = (GeneralUtility::makeInstance(HashService::class) instanceof HashService) ?
            GeneralUtility::makeInstance(HashService::class) :
            null;

        $this->persistenceManager = (GeneralUtility::makeInstance(PersistenceManager::class) instanceof PersistenceManager) ?
            GeneralUtility::makeInstance(PersistenceManager::class) :
            null;
    }

    /**
     * Actually convert from $source to $targetType, taking into account the fully
     * built $convertedChildProperties and $configuration.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface|null $configuration
     * @return object|null|\TYPO3\CMS\Extbase\Error\Error
     * @throws FileDoesNotExistException
     * @throws ResourceDoesNotExistException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     */
    public function convertFrom(
        $source,
        string $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        if (!isset($source['error']) || $source['error'] === UPLOAD_ERR_NO_FILE) {
            if (isset($source['submittedFile']['resourcePointer'])) {
                try {
                    $resourcePointer = (int)$this->hashService->validateAndStripHmac($source['submittedFile']['resourcePointer']);
                    if (strpos((string)$resourcePointer, 'file:') === 0) {
                        $fileUid = substr((string)$resourcePointer, 5);
                        return $this->createFileReferenceFromFalFileObject($this->resourceFactory->getFileObject((int)$fileUid),
                            $resourcePointer);
                    } else {
                        return $this->createFileReferenceFromFalFileReferenceObject($this->resourceFactory->getFileReferenceObject($resourcePointer),
                            $resourcePointer);
                    }
                } catch (InvalidArgumentException $e) {
                    // Nothing to do. No file is uploaded and resource pointer is invalid. Discard!
                }
            }
            return null;
        }

        if ($source['error'] !== UPLOAD_ERR_OK) {
            switch ($source['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                case UPLOAD_ERR_PARTIAL:
                    return new Error('Error Code: ' . $source['error'], 1264440823);
                default:
                    return new Error('An error occurred while uploading. Please try again or contact the administrator if the problem remains',
                        1340193849);
            }
        }

        if (isset($this->convertedResources[$source['tmp_name']])) {
            return $this->convertedResources[$source['tmp_name']];
        }

        try {
            $resource = $this->importUploadedResource($source, $configuration);
        } catch (Exception $e) {
            return new Error($e->getMessage(), $e->getCode());
        }

        $this->convertedResources[$source['tmp_name']] = $resource;
        return $resource;
    }

    /**
     * Import a resource and respect configuration given for properties
     *
     * @param array $uploadInfo
     * @param PropertyMappingConfigurationInterface $configuration
     * @return object
     * @throws TypeConverterException
     * @throws ExistingTargetFileNameException
     */
    protected function importUploadedResource(
        array $uploadInfo,
        PropertyMappingConfigurationInterface $configuration
    ): object {
        if (!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($uploadInfo['name'])) {
            throw new TypeConverterException('Uploading files with PHP file extensions is not allowed!', 1399312430);
        }

        $allowedFileExtensions = $configuration->getConfigurationValue(UploadedFileReferenceConverter::class,
            (string)self::CONFIGURATION_ALLOWED_FILE_EXTENSIONS);

        if ($allowedFileExtensions !== null) {
            $filePathInfo = PathUtility::pathinfo($uploadInfo['name']);
            if (!GeneralUtility::inList($allowedFileExtensions, strtolower($filePathInfo['extension']))) {
                throw new TypeConverterException('File extension is not allowed!', 1399312430);
            }
        }

        $uploadFolderId = $configuration->getConfigurationValue(UploadedFileReferenceConverter::class,
            (string)self::CONFIGURATION_UPLOAD_FOLDER) ?: $this->defaultUploadFolder;
        if (class_exists('TYPO3\\CMS\\Core\\Resource\\DuplicationBehavior')) {
            $defaultConflictMode = DuplicationBehavior::RENAME;
        } else {
            // @deprecated since 7.6 will be removed once 6.2 support is removed
            $defaultConflictMode = 'changeName';
        }
        $conflictMode = $configuration->getConfigurationValue(UploadedFileReferenceConverter::class,
            (string)self::CONFIGURATION_UPLOAD_CONFLICT_MODE) ?: $defaultConflictMode;

        $uploadFolder = $this->resourceFactory->retrieveFileOrFolderObject($uploadFolderId);
        $uploadedFile = $uploadFolder->addUploadedFile($uploadInfo, $conflictMode);

        $resourcePointer = isset($uploadInfo['submittedFile']['resourcePointer']) && strpos($uploadInfo['submittedFile']['resourcePointer'],
            'file:') === false
            ? $this->hashService->validateAndStripHmac($uploadInfo['submittedFile']['resourcePointer'])
            : null;

        return $this->createFileReferenceFromFalFileObject($uploadedFile, (int)$resourcePointer);
    }

    /**
     * @param FalFile $file
     * @param int $resourcePointer
     * @return object
     */
    protected function createFileReferenceFromFalFileObject(FalFile $file, int $resourcePointer): object
    {
        $fileReference = $this->resourceFactory->createFileReferenceObject(
            [
                'uid_local' => $file->getUid(),
                'uid_foreign' => uniqid('NEW_'),
                'uid' => uniqid('NEW_'),
                'crop' => null,
            ]
        );
        return $this->createFileReferenceFromFalFileReferenceObject($fileReference, $resourcePointer);
    }

    /**
     * @param FalFileReference $falFileReference
     * @param int|null $resourcePointer
     * @return object
     */
    protected function createFileReferenceFromFalFileReferenceObject(
        FalFileReference $falFileReference,
        int $resourcePointer = null
    ): object {
        if ($resourcePointer === null) {
            /** @var  \Mblunck\Registration\Domain\Model\FileReference $fileReference */
            $fileReference = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
        } else {
            $fileReference = $this->persistenceManager->getObjectByIdentifier($resourcePointer,
                'TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference', false);
        }

        $fileReference->setOriginalResource($falFileReference);

        return $fileReference;
    }
}
