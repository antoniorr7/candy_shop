<?php
require_once 'model/Cart.php';
require_once 'model/Product.php';
require_once 'model/Sale.php';

class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function add() {
        $response = ['success' => false, 'message' => ''];
    
        if ($_POST) {
            $product_id = $_POST['product_id'];
            $quantity = floatval($_POST['quantity']);
            $sale_type = $_POST['sale_type'] ?? 'unit'; // 'bag' o 'unit'
            
            $product = new Product();
            $product_data = $product->getById($product_id);
            
            if ($product_data) {
                // Determinar el nombre según el tipo de venta
                $item_name = $product_data['name'];
                if ($sale_type === 'bag') {
                    $item_name .= ' (Bolsa)';
                    $price = 1.00; // Precio fijo para bolsas
                } else {
                    $item_name .= ' (Suelto)';
                    $price = $product_data['price'];
                }
                
                $this->cart->addItem(
                    $product_id . '_' . $sale_type, // ID único para bolsa/suelto
                    $quantity, 
                    $price, 
                    $item_name,
                    $sale_type
                );
                
                $response['success'] = true;
                $response['message'] = 'Producto añadido al ticket';
                $response['cart_count'] = $this->cart->getItemCount();
                $response['cart_total'] = number_format($this->cart->getTotal(), 2);
            }
        }
        
        // Si es una petición AJAX, devolver JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // Si no es AJAX, redirigir de vuelta a la página anterior
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header('Location: ' . $referer);
        exit;
    }

    public function remove() {
        if (isset($_GET['product_id'])) {
            $this->cart->removeItem($_GET['product_id']);
        }
        
        header('Location: index.php?action=cart');
        exit;
    }

    public function update() {
        if ($_POST) {
            foreach ($_POST['quantities'] as $product_id => $quantity) {
                $this->cart->updateQuantity($product_id, floatval($quantity));
            }
        }
        
        header('Location: index.php?action=cart');
        exit;
    }

    public function view() {
        $title = "Carrito de Compras";
        $cart_items = $this->cart->getItems();
        $total = $this->cart->getTotal();
        include 'view/cart.php';
    }

    public function checkout() {
        if (!$this->cart->isEmpty()) {
            $sale = new Sale();
            $product = new Product();
            
            $sale->total = $this->cart->getTotal();
            $sale_id = $sale->create();
            
            if ($sale_id) {
                foreach ($this->cart->getItems() as $item) {
                    $subtotal = $item['quantity'] * $item['price'];
                    $sale->addDetail(
                        $sale_id, 
                        $item['product_id'], 
                        $item['quantity'], 
                        $item['price'], 
                        $subtotal
                    );
                    
                    // Actualizar stock
                    $product->updateStock($item['product_id'], $item['quantity']);
                }
                
                $this->cart->clear();
                $success_message = "Venta realizada correctamente. Total: €" . number_format($sale->total, 2);
            } else {
                $error_message = "Error al procesar la venta";
            }
        }
        
        $title = "Carrito de Compras";
        $cart_items = $this->cart->getItems();
        $total = $this->cart->getTotal();
        include 'view/cart.php';
    }

    public function clear() {
        $this->cart->clear();
        header('Location: index.php');
        exit;
    }
}
?>
