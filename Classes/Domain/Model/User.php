<?php

declare(strict_types=1);

namespace Mblunck\Registration\Domain\Model;


use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use DateTime;

/**
 * This file is part of the "registration" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Michael Blunck <mi.blunck@gmail>
 */

/**
 * User
 */
class User extends FrontendUser
{


    /**
     * @var string
     */
    public string $check = '';

    /**
     * birthday
     *
     * @var DateTime | null
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected ?DateTime $birthday = null;


    /**
     * Returns the birthday
     *
     * @return DateTime $birthday
     */
    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    /**
     * Sets the birthday
     *
     * @param DateTime $birthday
     * @return void
     */
    public function setBirthday(DateTime $birthday)
    {
        $this->birthday = $birthday;
    }
}
