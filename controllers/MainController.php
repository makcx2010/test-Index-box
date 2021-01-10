<?php

namespace controllers;

use models\repositories\BlogRepository;
use system\View;

class MainController extends View
{
    const DAY_IN_SECONDS = 86400;
    const DEFAULT_LIMIT = 10;

    public function actionIndex(): void
    {
        $blog = new BlogRepository;
        $data = $this->prepareSqlQuery();
        $query = $data['query'];
        $limit = $data['limit'];
        $page = $data['page'];
        $articles = $blog->all($query);
        $countPages = ceil($blog->getCount($query) / $limit);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['articles' => $articles, 'page' => $page + 1]);

            return;
        }

        self::render('Main', '/public/pages/index', ['articles' => $articles, 'countPages' => $countPages]);
    }

    public function actionView(): void
    {
        if (isset(explode('/', $_SERVER['REQUEST_URI'])[3])) {
            $href = '\'' . explode('/', $_SERVER['REQUEST_URI'])[3] . '\'';
        } else {
            throw new \DomainException('Href is empty');
        }

        $blog = new BlogRepository;
        $article = $blog->getByHref($href);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $blog->incrementView($href);

            echo json_encode(['success' => true]);

            return;
        }

        self::render('View', '/public/pages/view', ['article' => $article]);
    }

    private function prepareSqlQuery(): array
    {
        $query = '';
        $limit = self::DEFAULT_LIMIT;
        $page = 0;

        if (isset(explode('?', $_SERVER['REQUEST_URI'])[1])) {
            $getQuery = explode('?', $_SERVER['REQUEST_URI'])[1];
            parse_str($getQuery, $params);

            if (!empty($params)) {
                $query .= ' WHERE';

                foreach ($params as $key => $value) {
                    if ($value) {
                        if ($key === 'time_create') {
                            $startDate = strtotime($value);
                            $endDate = $startDate + self::DAY_IN_SECONDS;

                            $query .= ' AND ' . $key . '>=' . '\'' . $startDate . '\'';
                            $query .= ' AND ' . $key . '<=' . '\'' . $endDate . '\'';
                        } else {
                            if ($key === 'limit') {
                                if (is_numeric($value)) {
                                    $limit = $value;
                                }
                            } else {
                                if ($key === 'page') {
                                    if (is_numeric($value)) {
                                        $page = $value - 1;
                                    }
                                } else {
                                    $query .= ' AND ' . $key . '=' . '\'' . $value . '\'';
                                }
                            }
                        }
                    }
                }
                if ($query === ' WHERE') {
                    $query = '';
                } else {
                    $query = preg_replace('/AND/', '', $query, 1);
                }
            }
        }

        $query .= ' ORDER BY time_create DESC LIMIT ' . $limit . ' OFFSET ' . $page * $limit;

        return ['query' => $query, 'limit' => $limit, 'page' => $page];
    }
}