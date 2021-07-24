<?php
declare(strict_types=1);

namespace Mblunck\Registration\Tests\Unit\Controller;

use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Michael Blunck <mi.blunck@gmail>
 */
class UserControllerTest extends UnitTestCase
{
    /**
     * @var \Mblunck\Registration\Controller\UserController
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Mblunck\Registration\Controller\UserController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllUsersFromRepositoryAndAssignsThemToView(): void
    {
        $allUsers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository = $this->getMockBuilder(FrontendUserRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->expects(self::once())->method('findAll')->will(self::returnValue($allUsers));
        $this->inject($this->subject, 'userRepository', $userRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('users', $allUsers);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenUserToView():void
    {
        $user = new \Mblunck\Registration\Domain\Model\User();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('user', $user);

        $this->subject->showAction($user);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenUserToUserRepository():void
    {
        $user = new \Mblunck\Registration\Domain\Model\User();

        $userRepository = $this->getMockBuilder(FrontendUserRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->expects(self::once())->method('add')->with($user);
        $this->inject($this->subject, 'userRepository', $userRepository);

        try {
            $this->subject->createAction($user);
        } catch (StopActionException | UnsupportedRequestTypeException $e) {
        }
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUserToView():void
    {
        $user = new \Mblunck\Registration\Domain\Model\User();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('user', $user);

        $this->subject->editAction($user);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenUserInUserRepository():void
    {
        $user = new \Mblunck\Registration\Domain\Model\User();

        $userRepository = $this->getMockBuilder(FrontendUserRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->expects(self::once())->method('update')->with($user);
        $this->inject($this->subject, 'userRepository', $userRepository);

        $this->subject->updateAction($user);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenUserFromUserRepository() :void
    {
        $user = new \Mblunck\Registration\Domain\Model\User();

        $userRepository = $this->getMockBuilder(FrontendUserRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->expects(self::once())->method('remove')->with($user);
        $this->inject($this->subject, 'userRepository', $userRepository);

        $this->subject->deleteAction($user);
    }

    private function inject(
        \Mblunck\Registration\Controller\UserController $subject,
        string $string,
        \PHPUnit\Framework\MockObject\MockObject $userRepository
    ) :void {
    }
}
