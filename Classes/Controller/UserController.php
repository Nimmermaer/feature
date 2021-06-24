<?php

declare(strict_types=1);

namespace Mblunck\Registration\Controller;


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
class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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
     * @param \Mblunck\Registration\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function showAction(\Mblunck\Registration\Domain\Model\User $user)
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
     * action create
     *
     * @param \Mblunck\Registration\Domain\Model\User $newUser
     * @return string|object|null|void
     */
    public function createAction(\Mblunck\Registration\Domain\Model\User $newUser)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->add($newUser);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Mblunck\Registration\Domain\Model\User $user
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("user")
     * @return string|object|null|void
     */
    public function editAction(\Mblunck\Registration\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param \Mblunck\Registration\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function updateAction(\Mblunck\Registration\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->userRepository->update($user);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Mblunck\Registration\Domain\Model\User $user
     * @return string|object|null|void
     */
    public function deleteAction(\Mblunck\Registration\Domain\Model\User $user)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
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
