<?php

declare(strict_types=1);

namespace Mblunck\Registration\Controller;

use Mblunck\Registration\Domain\Model\User;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * This file is part of the "registration" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Michael Blunck <mi.blunck@gmail>
 */

/**
 * UserController
 */
class UserController extends ActionController
{

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     */
    private FrontendUserRepository $userRepository;

    protected function initializeCreateAction(): void
    {
        $this->setTypeConverterConfigurationForImageUpload('newUser');
    }

    protected function initializeEditAction(): void
    {
        $this->setTypeConverterConfigurationForImageUpload('user');
    }

    public function __construct()
    {
        $userService = GeneralUtility::makeInstance(UserService::class);
        $context = GeneralUtility::makeInstance(Context::class);
        $this->userRepository = GeneralUtility::makeInstance(FrontendUserRepository::class,
            $userService,
            $context
        );
    }

    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function listAction()
    {
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }

    /**
     * action show
     *
     * @param User $user
     * @return string|object|null|void
     */
    public function showAction(User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action new
     *
     * @return string|object|null|void
     */
    public function newAction()
    {
    }

    /**
     * @param User|null $newUser
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction(User $newUser = null): void
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html',
            '', AbstractMessage::WARNING);
        if ($newUser !== null) {
            $this->userRepository->add($newUser);
        }
        $this->redirect('edit', null, null, ['user', $newUser], 6);
    }

    /**
     * action edit
     *
     * @param User $user
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("user")
     * @return string|object|null|void
     */
    public function editAction(User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param User $user
     * @return string|object|null|void
     */
    public function updateAction(User $user)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html',
            '', AbstractMessage::WARNING);
        $this->userRepository->update($user);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param User $user
     * @return string|object|null|void
     */
    public function deleteAction(User $user)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html',
            '', AbstractMessage::WARNING);
        $this->userRepository->remove($user);
        $this->redirect('list');
    }

    /**
     * action subscribe
     *
     * @return string|object|null|void
     */
    public function subscribeAction()
    {
    }
}
