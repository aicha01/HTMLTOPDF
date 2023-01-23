<?php


if ($_GET["license"] && $_GET['url'])
{
    require __DIR__ . '/backend/htmlTopdfUrl.php';
}
else
{
    $request = $_SERVER['REQUEST_URI'];

    switch ($request) {

        case '':
            require __DIR__ . '/frontend/index.php';
            break;
    
        case '/':
            require __DIR__ . '/frontend/index.php';
            break;
    
        case '/home':
            require __DIR__ . '/frontend/home.php';
            break;
    
        case '/register':
            require __DIR__ . '/authentification/register.php';
            break;
    
        case '/password_reset':
            require __DIR__ . '/authentification/password_reset.php';
            break;
    
        case '':
            require __DIR__ . '/htmltopdf';
            break;
    
        case '/logout':
            require __DIR__ . '/authentification/logout.php';
            break;
            
        default:
            http_response_code(404);
            require __DIR__ . '/authentification/404.php';
            break;
    }
}


