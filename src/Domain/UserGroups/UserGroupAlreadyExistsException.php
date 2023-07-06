<?php

namespace App\Domain\UserGroups;

use App\Domain\DomainException\DomainRecordAlreadyExistsException;

class UserGroupAlreadyExistsException extends DomainRecordAlreadyExistsException
{
    public $message = 'The user group already exists.';

}
