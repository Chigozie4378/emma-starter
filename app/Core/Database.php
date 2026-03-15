<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = config('database');

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->pdo;
    }

    public static function transaction(callable $callback)
    {
        $pdo = self::connect();

        try {
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }

            $result = $callback($pdo);

            if ($pdo->inTransaction()) {
                $pdo->commit();
            }

            return $result;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }
}