<?php
declare(strict_types=1);

namespace App\Form;

use Pimcore\Model\Element\ValidationException;
use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;

trait PasswordMaxLengthTrait
{
    use CheckPasswordLengthTrait;

    /**
     * @throws ValidationException
     */
    public function checkPassword(string $password): void
    {
        if ($this->isPasswordTooLong($password)) {
            throw new ValidationException("Given password is too long.");
        }
    }
}
