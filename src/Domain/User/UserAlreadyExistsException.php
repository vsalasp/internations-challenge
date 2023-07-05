<?php

namespace App\Domain\User;

use App\Domain\DomainException\DomainRecordAlreadyExistsException;

class UserAlreadyExistsException extends DomainRecordAlreadyExistsException
{
    public $message = 'The user already exists.';
}
