<?php
session_start();

// Autoload de clases
spl_autoload_register(function ($class_name) {
    $directories = ['controller/', 'model/'];
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'home';

// Enrutamiento
try {
    switch ($action) {
        case 'home':
        default:
            $controller = new HomeController();
            $controller->index();
            break;
            
        case 'caramelos':
            $controller = new HomeController();
            $controller->caramelos();
            break;
            
        case 'articulos':
            $controller = new HomeController();
            $controller->articulos();
            break;
            
        case 'nevera':
            $controller = new HomeController();
            $controller->nevera();
            break;
            
        case 'cart':
            $controller = new CartController();
            $controller->view();
            break;
            
        case 'add_to_cart':
            $controller = new CartController();
            $controller->add();
            break;
            
        case 'remove_from_cart':
            $controller = new CartController();
            $controller->remove();
            break;
            
        case 'update_cart':
            $controller = new CartController();
            $controller->update();
            break;
            
        case 'checkout':
            $controller = new CartController();
            $controller->checkout();
            break;

        case 'clear_cart':
            $controller = new CartController();
            $controller->clear();
            break;
            
        case 'admin':
            $controller = new AdminController();
            $controller->index();
            break;
            
        case 'add_product':
            $controller = new AdminController();
            $controller->addProduct();
            break;
            
        case 'update_product':
            $controller = new AdminController();
            $controller->updateProduct();
            break;
            
        case 'delete_product':
            $controller = new AdminController();
            $controller->deleteProduct();
            break;
    }
} catch (Exception $e) {
    // Manejo básico de errores
    echo "Error: " . $e->getMessage();
}
?>
