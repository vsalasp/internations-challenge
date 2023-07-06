<?php

namespace App\Infrastructure\Persistence\UserGroup;

use App\Domain\User\User;
use App\Domain\UserGroups\UserGroup;
use App\Domain\UserGroups\UserGroupAlreadyExistsException;
use App\Domain\UserGroups\UserGroupNotFoundException;
use App\Domain\UserGroups\UserGroupRepository;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\UserList;
use App\Infrastructure\Persistence\Common\MySQLRepository;
use PDO;

class MySQLUserGroupRepository extends MySQLRepository implements UserGroupRepository
{
    public function findAll(): array
    {
        $statement = $this->getConnection()->query("SELECT * FROM user_groups");

        $result = [];
        foreach ($statement->fetchAll() as $row) {
            $result[] = new UserGroup(
                new ID($row['id']),
                new Name($row['name']),
                $this->getUserGroupMembers($row['id']),
            );
        }
        return $result;
    }

    private function getUserGroupMembers(int $userGroupId): UserList
    {
        $membersStatement = $this->getConnection()->prepare("
                SELECT * 
                FROM users u
                JOIN user_group_members ugm on u.id = ugm.user_id
                WHERE ugm.group_id = :groupId
            ");
        $membersStatement->bindValue('groupId', $userGroupId);
        $membersStatement->execute();

        return new UserList(array_map(fn ($userRow) => new User(
            new ID($userRow['id']),
            new Name($userRow['name']),
        ), $membersStatement->fetchAll()));
    }

    public function findUserGroupOfId(ID $id): UserGroup
    {
        $userGroupID = $id->getValue();
        $statement = $this->getConnection()->prepare('SELECT * from user_groups WHERE id = :id LIMIT 1');
        $statement->bindValue('id', $userGroupID, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row) {
            throw new UserGroupNotFoundException();
        }

        return new UserGroup(
            new ID($row['id']),
            new Name($row['name']),
            $this->getUserGroupMembers($row['id']),
        );
    }

    public function addUserGroup(UserGroup $group): UserGroup
    {
        $name = $group->getName();
        $statement = $this->getConnection()->prepare('SELECT * FROM user_groups WHERE name = :name LIMIT 1');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch();

        if ($row) {
            throw new UserGroupAlreadyExistsException();
        }

        $statement = $this->getConnection()->prepare('INSERT INTO user_groups (name) VALUES (:name)');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->execute();

        return new UserGroup(
            new ID($this->getConnection()->lastInsertId()),
            $group->getName(),
            $group->getMembers()
        );
    }

    public function addMemberToUserGroup(UserGroup $group, User $user): void
    {
        $groupID = $group->getId()->getValue();
        $userId = $user->getID()->getValue();
        $statement = $this->getConnection()->prepare(
            "INSERT INTO user_group_members (group_id, user_id) VALUES (:groupId, :userId)"
        );
        $statement->bindParam("groupId", $groupID);
        $statement->bindParam("userId", $userId);
        $statement->execute();
    }

    public function deleteUserGroup(UserGroup $group): void
    {
        $id = $group->getId()->getValue();
        $statement = $this->getConnection()->prepare('DELETE FROM user_groups WHERE id = :id');
        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
