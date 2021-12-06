<?php

declare(strict_types=1);

namespace Mblunck\Registration\Controller;

use Mblunck\Registration\Domain\Model\User;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
     * @var FrontendUserRepository|null
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

    protected function initializeUpdateAction(): void
    {
        $this->setTypeConverterConfigurationForImageUpload('user');
    }

    public function __construct()
    {
        $this->userRepository = GeneralUtility::makeInstance(FrontendUserRepository::class) ;
    }

    /**
     *
     */
    public function listAction(): void
    {
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }

    /**
     * @param User $user
     */
    public function showAction(User $user): void
    {
        $this->view->assign('user', $user);
    }



    /**
     * @param User|null $newUser
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     */
    public function createAction(User $newUser = null): void
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html',
            '', AbstractMessage::WARNING);
        if ($newUser !== null) {
            $newUser->setUsername($newUser->getEmail());
            $this->userRepository->add($newUser);
            /** @var PersistenceManager $persistenceManager */
            $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
            $persistenceManager->persistAll();
        }

        $this->redirect('edit', null, null, ['user' => $newUser], 6);
    }

    /**
     * @param User $user
     */
    public function editAction(User $user)
    {
        dd($user);
        $this->view->assign('user', $user);
    }

    /**
     * @param User $user
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function updateAction(User $user): void
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html',
            '', AbstractMessage::WARNING);
        $this->userRepository->update($user);
        $this->redirect('list');
    }

    /**
     * @param User $user
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(User $user): void
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
    public function subscribeAction(): void
    {
    }
}
