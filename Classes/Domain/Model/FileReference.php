<?php


namespace Mblunck\Registration\Domain\Model;

use TYPO3\CMS\Core\Resource\ResourceInterface;

/**
 * Class FileReference
 * @package Mblunck\Registration\Domain\Model
 */
class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{

    public string $type = '';
    public string $tmp_name = '';
    public string $error = '';
    public string $size = '';
    protected string $name = '';

    /**
     * Uid of a sys_file
     *
     * @var int|null
     */
    protected ?int $originalFileIdentifier = null;

    /**
     * @param ResourceInterface $originalResource
     */
    public function setOriginalResource(ResourceInterface $originalResource):void
    {
        $this->setFileReference($originalResource);
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileReference $originalResource
     */
    private function setFileReference(\TYPO3\CMS\Core\Resource\FileReference $originalResource):void
    {
        $this->originalResource = $originalResource;
        $this->originalFileIdentifier = $originalResource->getOriginalFile()->getUid();
        $this->uidLocal = $originalResource->getOriginalFile()->getUid();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
