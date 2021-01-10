<?php

use system\Database;

class m0001_init extends Database
{
    public function up()
    {
        $this->createProducts();
        $this->createBlog();

        $this->pdo->exec("CREATE INDEX `idx-product-name` ON products (name);");
        $this->pdo->exec("CREATE INDEX `idx-blog-product` ON blog (product);");
        $this->pdo->exec("CREATE INDEX `idx-blog-views` ON blog (views);");
        $this->pdo->exec("CREATE INDEX `idx-blog-time_create` ON blog (time_create);");
        $this->pdo->exec("ALTER TABLE blog ADD FOREIGN KEY (`product`) REFERENCES products(`name`);");
    }

    private function createProducts()
    {
        $products = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'products.json');
        $products = json_decode($products, true);
        $structure = $this->prepareStructure($products['columns']);

        $this->pdo->exec("CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY," .
                         $structure . "
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");

        foreach ($products['data'] as $row) {
            $result = $this->prepareRow($row);

            $this->pdo->exec("INSERT INTO products ({$result['columns']}) VALUES ({$result['values']});");
        }
    }

    private function createBlog()
    {
        $blog = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'blog.json');
        $blog = json_decode($blog, true);
        $structure = $this->prepareStructure($blog['columns']);

        $this->pdo->exec("CREATE TABLE blog (
            id INT AUTO_INCREMENT PRIMARY KEY," .
                         $structure . "
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");

        foreach ($blog['data'] as $row) {
            $result = $this->prepareRow($row);

            $this->pdo->exec("INSERT INTO blog ({$result['columns']}) VALUES ({$result['values']});");
        }
    }

    private function prepareStructure($data): string
    {
        $structure = '';

        foreach ($data as $name => $type) {
            $structure .= $name . ' ' . $type . ', ';
        }

        return $structure;
    }

    private function prepareRow($row): array
    {
        $columns = '';
        $values = '';

        foreach ($row as $column => $value) {
            if ($columns === '' && $values === '') {
                $columns .= '`' . addcslashes($column, "'") . '`';
                $values .= '\'' . addcslashes($value, "'") . '\'';
            } else {
                $columns .= ', `' . addcslashes($column, "'") . '`';
                $values .= ', \'' . addcslashes($value, "'") . '\'';
            }
        }

        return ['columns' => $columns, 'values' => $values];
    }
}