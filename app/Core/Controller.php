<?php

class Controller
{
    protected function view($view, $data = [], $layout = 'app')
    {
        extract($data);

        $viewPath = view_path($view);

        if (!file_exists($viewPath)) {
            die("View {$view} not found.");
        }

        if ($layout === null) {
            require $viewPath;
            return;
        }

        $layoutPath = view_path('layouts.' . $layout);

        if (!file_exists($layoutPath)) {
            die("Layout {$layout} not found.");
        }

        $contentView = $viewPath;
        require $layoutPath;
    }

    protected function json($data, $status = 200)
    {
        Response::json($data, $status);
    }

    protected function redirect($url)
    {
        Response::redirect($url);
    }
}