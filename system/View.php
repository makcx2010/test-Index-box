<?php

namespace system;

class View
{
    public static function render(string $title, string $path, array $data = [], string $layout = 'main')
    {
        $fullPath = App::$ROOT_DIR . $path . '.php';

        if (!file_exists($fullPath)) {
            throw new \DomainException('file is not found');
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        ob_start();
        require_once($fullPath);
        $content = ob_get_clean();

        return require_once(App::$ROOT_DIR . '/public/layouts' . DIRECTORY_SEPARATOR . $layout . '.php');
    }
}