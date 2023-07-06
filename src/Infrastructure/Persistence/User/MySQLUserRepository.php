<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\Name;
use App\Infrastructure\Persistence\Common\MySQLRepository;
use PDO;

class MySQLUserRepository extends MySQLRepository implements UserRepository
{
    public function findAll(): array
    {
        $statement = $this->getConnection()->query('SELECT * FROM users');

        $result = [];
        foreach ($statement->fetchAll() as $rows) {
            $result[] = new User(
                new ID($rows['id']),
                new Name($rows['name']),
            );
        }
        return $result;
    }

    public function findUserOfId(ID $id): User
    {
        $userId = $id->getValue();
        $statement = $this->getConnection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->bindParam('id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException();
        }
        return new User(
            new ID($row['id']),
            new Name($row['name'])
        );
    }

    public function addUser(User $user): User
    {
        $name = $user->getName()->getValue();
        $statement = $this->getConnection()->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch();

        if ($row) {
            throw new UserAlreadyExistsException();
        }

        $statement = $this->getConnection()->prepare('INSERT INTO users (name) VALUES (:name)');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->execute();

        return new User(
            new ID($this->getConnection()->lastInsertId()),
            $user->getName(),
        );
    }

    public function deleteUser(ID $userId): void
    {
        $this->findUserOfId($userId);

        $id = $userId->getValue();
        $statement = $this->getConnection()->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
