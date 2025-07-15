<?php
class Cart {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    public function addItem($product_id, $quantity, $price, $name, $sale_type = 'unit') {
        $item_key = $product_id;
        
        if (isset($_SESSION['cart'][$item_key])) {
            $_SESSION['cart'][$item_key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$item_key] = array(
                'product_id' => $product_id,
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'sale_type' => $sale_type
            );
        }
    }

    public function removeItem($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function updateQuantity($product_id, $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity <= 0) {
                $this->removeItem($product_id);
            } else {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
    }

    public function getItems() {
        return $_SESSION['cart'];
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    public function getItemCount() {
        return count($_SESSION['cart']);
    }

    public function clear() {
        $_SESSION['cart'] = array();
    }

    public function isEmpty() {
        return empty($_SESSION['cart']);
    }
}
?>
