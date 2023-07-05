<?php

namespace App\Domain\UserGroups;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UserGroupNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user group you requested does not exist.';
}
