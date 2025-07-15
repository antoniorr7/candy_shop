<?php
require_once 'model/Product.php';
require_once 'model/Sale.php';

class AdminController {
    public function index() {
        $sale = new Sale();
        $product = new Product();
        
        $daily_sales = $sale->getDailySales();
        $monthly_sales = $sale->getMonthlySales();
        $recent_sales = $sale->getRecentSales(5);
        $products = $product->getAll();
        
        $title = "Administración";
        include 'view/admin.php';
    }

    public function addProduct() {
        if ($_POST) {
            $product = new Product();
            $product->name = $_POST['name'];
            $product->category_id = $_POST['category_id'];
            $product->price = $_POST['price'];
            $product->stock = $_POST['stock'];
            $product->is_weight_based = isset($_POST['is_weight_based']) ? 1 : 0;
            
            if ($product->create()) {
                $success_message = "Producto agregado correctamente";
            } else {
                $error_message = "Error al agregar el producto";
            }
        }
        
        header('Location: index.php?action=admin');
        exit;
    }

    public function updateProduct() {
        if ($_POST) {
            $product = new Product();
            $product->id = $_POST['id'];
            $product->name = $_POST['name'];
            $product->category_id = $_POST['category_id'];
            $product->price = $_POST['price'];
            $product->stock = $_POST['stock'];
            $product->is_weight_based = isset($_POST['is_weight_based']) ? 1 : 0;
            
            if ($product->update()) {
                $success_message = "Producto actualizado correctamente";
            } else {
                $error_message = "Error al actualizar el producto";
            }
        }
        
        header('Location: index.php?action=admin');
        exit;
    }

    public function deleteProduct() {
        if (isset($_GET['id'])) {
            $product = new Product();
            $product->id = $_GET['id'];
            
            if ($product->delete()) {
                $success_message = "Producto eliminado correctamente";
            } else {
                $error_message = "Error al eliminar el producto";
            }
        }
        
        header('Location: index.php?action=admin');
        exit;
    }
}
?>
