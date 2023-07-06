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

        if (!empty($group->getMembers()->getValue())) {
            $this->addMembersToUserGroupOfId($group->getId(), $group->getMembers());
        }

        return new UserGroup(
            new ID($this->getConnection()->lastInsertId()),
            $group->getName(),
            $group->getMembers()
        );
    }

    public function addMembersToUserGroupOfId(ID $groupId, UserList $users): void
    {
        $sql = "INSERT INTO user_group_members (group_id, user_id) VALUES ";
        $sql .= implode(', ', array_fill(0, count($users->getValue()), "(?, ?)"));
        $statement = $this->getConnection()->prepare($sql);
        foreach ($users->getValue() as $index => $user) {
            $userId = $user->getID();
            $statement->bindParam($index * 2 + 1, $groupId);
            $statement->bindParam($index * 2 + 2, $userId);
        }

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
