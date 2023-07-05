<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\ValueObjects\ID;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param ID $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(ID $id): User;

    /**
     * @param User $user
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function addUser(User $user): User;

    /**
     * @param ID $userId
     * @throws UserNotFoundException
     */
    public function deleteUser(ID $userId): void;
}
