<?php
namespace App;
use AltoRouter;

class Router {
    private $viewPath;
    private $isAjax;
    private $router;

    function __construct(string $viewPath, bool $isAjax = FALSE)
    {
        $this->viewPath = $viewPath;
        $this->isAjax = $isAjax;
        $this->router = new AltoRouter();
    }

    function request(string $url, string $view, ?string $name = null, string $method = 'GET')
    {
        $this->router->map($method, $url, $view, $name);
        return $this;
    }

    function requestAjax(string $url, string $name, string $method = 'GET')
    {
        $this->router->map($method, $url, '/ajax/controller.php', $name);
        return $this;
    }
    
    function url (string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'] ?? '404.php';
        $params = $match['params'] ?? '';
        $router = $this;
        Driver::getPDO();
        ob_start();
        require_once $this->viewPath . DIRECTORY_SEPARATOR . $view;
        $content = ob_get_clean();
        require_once $this->viewPath . DIRECTORY_SEPARATOR . 'layout.php';
        return $this;
    }
}