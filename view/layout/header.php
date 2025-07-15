<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'TPV Chuches'; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-left">
                <a href="index.php" class="logo">TPV CHUCHES</a>
            </div>
            <div class="header-right">
                <a href="index.php?action=cart" class="cart-link">
                    <?php 
                    $cart = new Cart();
                    if (!$cart->isEmpty()) {
                        echo 'TICKET (' . $cart->getItemCount() . ') - €' . number_format($cart->getTotal(), 2);
                    } else {
                        echo 'TICKET VACÍO';
                    }
                    ?>
                </a>
                <a href="index.php?action=admin" class="admin-link">ADMIN</a>
            </div>
        </div>
    </header>
    <main class="main">
