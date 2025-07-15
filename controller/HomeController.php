<?php
require_once 'model/Product.php';

class HomeController {
    public function index() {
        $title = "Tienda de Chuches - TPV";
        include 'view/home.php';
    }

    public function caramelos() {
        $product = new Product();
        $products = $product->getByCategory(1);
        $title = "Caramelos";
        $category = "caramelos";
        include 'view/caramelos.php';
    }

    public function articulos() {
        $product = new Product();
        $products = $product->getByCategory(2);
        $title = "Artículos Sueltos";
        $category = "articulos";
        include 'view/articulos.php';
    }

    public function nevera() {
        $product = new Product();
        $products = $product->getByCategory(3);
        $title = "Nevera";
        $category = "nevera";
        include 'view/nevera.php';
    }
}
?>
