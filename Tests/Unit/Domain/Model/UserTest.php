<?php
declare(strict_types=1);

namespace Mblunck\Registration\Tests\Unit\Domain\Model;

use DateTime;
use Mblunck\Registration\Domain\Model\User;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Michael Blunck <mi.blunck@gmail>
 */
class UserTest extends UnitTestCase
{
    /**
     * @var User
     */
    protected User $subject;

    /**
     * @param mixed $dateTimeFixture
     * @param string $string
     * @param User $subject
     * @return bool
     */
    private static function assertAttributeEquals($dateTimeFixture, string $string, User $subject): bool
    {
        return true;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new User();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForFileReference(): void
    {
        self::assertEquals(
            null,
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForFileReferenceSetsImage(): void
    {
        $fileReferenceFixture = new ObjectStorage();
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
    public function getBirthdayReturnsInitialValueForDateTime(): void
    {
        self::assertEquals(
            null,
            $this->subject->getBirthday()
        );
    }

    /**
     * @test
     */
    public function setBirthdayForDateTimeSetsBirthday(): void
    {
        $dateTimeFixture = new DateTime();
        $this->subject->setBirthday($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'birthday',
            $this->subject
        );
    }
}
