<?php
require_once 'model/Cart.php';
include 'view/layout/header.php';
?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← Volver</a>
        <h2>🥤 NEVERA</h2>
        <span class="info-icon" onclick="toggleInfo(this);">ℹ️</span>
        <div class="info-tooltip">
            Bebidas frías y productos refrigerados. Refrescos, agua,
            bebidas energéticas y más.
        </div>
    </div>

    <div class="products-grid">
        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="price">€<?php echo number_format($row['price'], 2); ?></p>
                    <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="stock">Stock: <?php echo $row['stock']; ?></p>
                </div>

                <form method="POST" action="index.php?action=add_to_cart" class="add-form">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

                    <div class="quantity-section">
                        <label>Cantidad:</label>
                        <div class="quantity-controls">
                            <button type="button" onclick="changeQuantity(this, -1)" class="qty-control">-</button>
                            <input type="number" name="quantity" value="1" min="1" class="qty-input">
                            <button type="button" onclick="changeQuantity(this, 1)" class="qty-control">+</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function changeQuantity(button, change) {
        const input = button.parentNode.querySelector('.qty-input');
        const currentValue = parseInt(input.value) || 1;
        const newValue = Math.max(1, currentValue + change);
        input.value = newValue;
    }

    function toggleInfo(icon) {
        let tooltip = icon.nextElementSibling;
        tooltip.classList.toggle("show");
    }

    // Close tooltip when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.matches('.info-icon')) {
            let tooltips = document.querySelectorAll('.info-tooltip');
            tooltips.forEach(function(tooltip) {
                if (tooltip.classList.contains('show')) {
                    tooltip.classList.remove('show');
                }
            });
        }
    });
</script>

<?php include 'view/layout/footer.php'; ?>
