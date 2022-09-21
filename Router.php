<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {
        session_start();

        // $currentUrl = $_SERVER['PATH_INFO'] ?? '/'; // For developing
        $currentUrl = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI']; //For production deployment
        $method = $_SERVER['REQUEST_METHOD'];
    
        //Split currentURL if '?'
        $splitURL = explode('?', $currentUrl);

        if ($method === 'GET') {
            $fn = $this->getRoutes[$splitURL[0]] ?? null; //$splitURL[0] url without vars
        } else {
            $fn = $this->postRoutes[$splitURL[0]] ?? null;
        }

        if ( $fn ) {
            // If route is correct, execute function
            call_user_func($fn, $this);
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    public function render($view, $datos = [])
    {
        // Read variables on view
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        //Keep on memory
        ob_start();
        
        //Include variables on view
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); //Clean buffer
        include_once __DIR__ . '/views/layout.php';
    }
}
