<?php
require_once(__DIR__ . '/../config.php');

class BaseController
{
    /**
     * Helper function to render a view.
     *
     * @param string $view The view file to render.
     * @param array $data The data to pass to the view.
     */
    protected function view($view, $data = [])
    {
        extract($data);
        ob_start();
        require_once(__DIR__ . "/../views/{$view}.php");
        $content = ob_get_clean();
        require_once(__DIR__ . "/../views/layouts/layout.php"); // Fix the layout path
    }

    /**
     * Helper function to handle redirections.
     *
     * @param string $url The URL to redirect to.
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
