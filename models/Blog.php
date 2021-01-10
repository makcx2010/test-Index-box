<?php

namespace models;

use system\Database;

class Blog extends BaseModel
{
    public int $id;
    public string $href;
    public string $title;
    public string $body;
    public string $description;
    public string $product;
    public string $views;
    public int $time_create;
    public int $created_at;

    public function edit($data)
    {
        $this->loadData($data);
        // save through repository
    }
}