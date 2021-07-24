<?php


namespace Mblunck\Registration\Domain\Validator;


use Mblunck\Registration\Domain\Model\User;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 * Class UserValidator
 * @package Mblunck\Registration\Domain\Validator
 */
class UserValidator extends AbstractValidator
{

    /**
     * @inheritDoc
     */
    protected function isValid($value): void
    {
        /** @var User $value */
        if (!($value->getPassword() === $value->check)) {
            $this->addError(
                'Password nicht übereinstimmend',
                1238108067
            );
        }
    }
}
