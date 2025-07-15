<?php 
require_once 'model/Cart.php';
include 'view/layout/header.php'; 
?>

<div class="container">
    <div class="tpv-header">
        <h1>TPV - TIENDA DE CHUCHES</h1>
    </div>

    <div class="zones-grid">
        <a href="index.php?action=caramelos" class="zone-btn">
            <div class="zone-title">CARAMELOS</div>
            <div class="zone-desc">Dulces a granel</div>
        </a>

        <a href="index.php?action=articulos" class="zone-btn">
            <div class="zone-title">ARTICULOS</div>
            <div class="zone-desc">Chicles y chocolates</div>
        </a>

        <a href="index.php?action=nevera" class="zone-btn">
            <div class="zone-title">NEVERA</div>
            <div class="zone-desc">Bebidas frías</div>
        </a>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
    <div class="current-ticket">
        <div class="ticket-info">
            <span>TICKET ACTUAL: <?php echo count($_SESSION['cart']); ?> artículos</span>
            <span>TOTAL: €<?php 
                $cart = new Cart();
                echo number_format($cart->getTotal(), 2); 
            ?></span>
        </div>
        <a href="index.php?action=cart" class="btn-primary">VER TICKET</a>
    </div>
    <?php endif; ?>
</div>

<?php include 'view/layout/footer.php'; ?>
