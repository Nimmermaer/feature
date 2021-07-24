<?php


namespace Mblunck\Registration\Domain\Model;

use TYPO3\CMS\Core\Resource\ResourceInterface;

class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{
    /**
     * Uid of a sys_file
     *
     * @var int|null
     */
    protected int $originalFileIdentifier;

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
}
