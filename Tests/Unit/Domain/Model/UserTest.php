<?php
declare(strict_types=1);

namespace Mblunck\Registration\Tests\Unit\Domain\Model;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Michael Blunck <mi.blunck@gmail>
 */
class UserTest extends UnitTestCase
{
    /**
     * @var \Mblunck\Registration\Domain\Model\User
     */
    protected $subject;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Mblunck\Registration\Domain\Model\User();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForFileReferenceSetsImage()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setImage($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'image',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBirthdayReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getBirthday()
        );
    }

    /**
     * @test
     */
    public function setBirthdayForDateTimeSetsBirthday()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setBirthday($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'birthday',
            $this->subject
        );
    }
}
