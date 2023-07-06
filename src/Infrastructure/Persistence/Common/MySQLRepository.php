<?php

namespace App\Infrastructure\Persistence\Common;

use PDO;

abstract class MySQLRepository
{
    public function __construct(
        private PDO $database
    ) {
    }

    protected function getConnection(): PDO
    {
        return $this->database;
    }
}
