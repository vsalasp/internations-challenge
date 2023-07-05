<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Domain\ValueObjects\Common\BoolObject;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\Name;
use PDO;

class MySQLUserRepository implements UserRepository
{
    public function __construct(
        private PDO $database
    ) {
    }

    public function findAll(): array
    {
        $statement = $this->database->query('SELECT * FROM users');

        $result = [];
        foreach ($statement->fetchAll() as $rows) {
            $result[] = new User(
                new ID($rows['id']),
                new Name($rows['name']),
                new BoolObject($rows['is_admin']),
            );
        }
        return $result;
    }

    public function findUserOfId(ID $id): User
    {
        $userId = $id->getValue();
        $statement = $this->database->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->bindParam('id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException();
        }
        return new User(
            new ID($row['id']),
            new Name($row['name']),
            new BoolObject($row['is_admin']),
        );
    }

    public function addUser(User $user): User
    {
        $name = $user->getName()->getValue();
        $isAdmin = $user->getIsAdmin()->isValue();
        $statement = $this->database->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch();

        if ($row) {
            throw new UserAlreadyExistsException();
        }

        $statement = $this->database->prepare('INSERT INTO users (name, is_admin) VALUES (:name, :isAdmin)');
        $statement->bindParam('name', $name, PDO::PARAM_STR);
        $statement->bindParam('isAdmin', $isAdmin, PDO::PARAM_BOOL);
        $statement->execute();

        return new User(
            new ID($this->database->lastInsertId()),
            $user->getName(),
            $user->getIsAdmin()
        );
    }

    public function deleteUser(ID $userId): void
    {
        $this->findUserOfId($userId);

        $id = $userId->getValue();
        $statement = $this->database->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindParam('id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
