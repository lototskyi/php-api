<?php

class Database
{
    public function __construct(
        private readonly string $host,
        private readonly string $name,
        private readonly string $user,
        private readonly string $password
    ) {

    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}