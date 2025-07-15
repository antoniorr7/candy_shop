<?php 
require_once 'model/Cart.php';
include 'view/layout/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← VOLVER</a>
        <h2>CARAMELOS</h2>
    </div>

    <div id="message-area"></div>

    <div class="products-list">
        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="product-item">
            <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
            <div class="product-actions">
                <!-- Venta por bolsa -->
                <form class="product-form" data-type="bag">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="sale_type" value="bag">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-product">BOLSA €1.00</button>
                </form>
                
                <!-- Venta suelta -->
                <form class="product-form" data-type="unit">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="sale_type" value="unit">
                    <div class="quantity-input">
                        <input type="number" name="quantity" value="1" min="1" step="1">
                        <span class="unit-price">x €<?php echo number_format($row['price'], 2); ?></span>
                    </div>
                    <button type="submit" class="btn-product">SUELTO</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.product-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('index.php?action=add_to_cart', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    updateCartInfo(data.cart_count, data.cart_total);
                } else {
                    showMessage('Error al añadir producto', 'error');
                }
            })
            .catch(error => {
                showMessage('Error de conexión', 'error');
            });
        });
    });
});

function showMessage(message, type) {
    const messageArea = document.getElementById('message-area');
    messageArea.innerHTML = `<div class="message ${type}">${message}</div>`;
    setTimeout(() => {
        messageArea.innerHTML = '';
    }, 2000);
}

function updateCartInfo(count, total) {
    const cartLink = document.querySelector('.cart-link');
    if (cartLink) {
        cartLink.innerHTML = `TICKET (${count}) - €${total}`;
    }
}
</script>

<?php include 'view/layout/footer.php'; ?>
