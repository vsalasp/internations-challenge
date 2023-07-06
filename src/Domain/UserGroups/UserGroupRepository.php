<?php

namespace App\Domain\UserGroups;

use App\Domain\User\User;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\UserList;

interface UserGroupRepository
{
    /**
     * @return UserGroup[]
     */
    public function findAll(): array;

    /**
     * @param ID $id
     * @return UserGroup
     * @throws UserGroupNotFoundException
     */
    public function findUserGroupOfId(ID $id): UserGroup;

    /**
     * @param UserGroup $group
     * @return UserGroup
     * @throws UserGroupAlreadyExistsException
     */
    public function addUserGroup(UserGroup $group): UserGroup;


    /**
     * @param ID $groupId
     * @param UserList $users
     * @return void
     */
    public function addMembersToUserGroupOfId(ID $groupId, UserList $users): void;

    /**
     * @param UserGroup $group
     * @return void
     */
    public function deleteUserGroup(UserGroup $group): void;
}
