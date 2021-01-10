<?php

namespace models\repositories;

use system\Database;

class BlogRepository extends Database
{
    public $tableName = 'blog';

    public function all($query = ''): ?array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->tableName . $query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByHref($href): ?array
    {
        $response = $this->pdo->query('SELECT * FROM ' . $this->tableName . ' WHERE href=' . $href . ' LIMIT 1')->fetch(\PDO::FETCH_ASSOC);

        if (!$response) {
            throw new \DomainException('Article is not found');
        }

        return $response;
    }

    public function incrementView($href): void
    {
        $statement = $this->pdo->prepare('UPDATE ' . $this->tableName . ' SET views = views + 1 WHERE href=' . $href . ';');
        $statement->execute();
    }

    public function getCount($query = ''): int
    {
        return $this->pdo->query('SELECT COUNT(*) FROM ' . $this->tableName . $query)->fetchColumn();
    }

    public function save($id, $query)
    {
        $statement = $this->pdo->prepare('UPDATE ' . $this->tableName . $query . ' WHERE id=' . $id . ';');
        $statement->execute();
    }
}