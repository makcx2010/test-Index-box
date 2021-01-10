<?php

namespace system;

class Database
{
    /** @var \PDO $pdo */
    public $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=' . getenv('host') . ';dbname=' . getenv('dbname');
        $this->pdo = new \PDO($dsn, getenv('username'), getenv('password'));
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();
        $files = scandir(App::$ROOT_DIR . DIRECTORY_SEPARATOR . 'console' . DIRECTORY_SEPARATOR . 'migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $newMigrations = [];

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once App::$ROOT_DIR . DIRECTORY_SEPARATOR . 'console' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $migration;

            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            echo "Applying migration $migration" . PHP_EOL;
            $instance->up();
            echo "Applied migration $migration" . PHP_EOL;

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo 'All migrations are applied';
        }
    }

    public function createMigrationsTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations(): array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
            $str
            ");
        $statement->execute();
    }
}