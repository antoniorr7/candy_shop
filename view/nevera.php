<?php 
require_once 'model/Cart.php';
include 'view/layout/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← VOLVER</a>
        <h2>NEVERA</h2>
    </div>

    <div id="message-area"></div>

    <div class="products-list">
        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="product-item">
            <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
            <div class="product-actions">
                <form class="product-form">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="sale_type" value="unit">
                    <div class="quantity-controls">
                        <button type="button" onclick="changeQty(this, -1)">-</button>
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="button" onclick="changeQty(this, 1)">+</button>
                    </div>
                    <button type="submit" class="btn-add">AÑADIR</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function changeQty(btn, change) {
    const input = btn.parentNode.querySelector('input[name="quantity"]');
    const newValue = Math.max(1, parseInt(input.value) + change);
    input.value = newValue;
}

let messageTimeout;
function showMessage(message, type) {
    const messageArea = document.getElementById('message-area');
    messageArea.innerHTML = `<div class="message ${type}">${message}</div>`;
    if (messageTimeout) clearTimeout(messageTimeout);
    messageTimeout = setTimeout(() => {
        messageArea.innerHTML = '';
    }, 2000);
}

function updateCartInfo(count, total) {
    const cartLink = document.querySelector('.cart-link');
    if (cartLink) {
        cartLink.innerHTML = `TICKET (${count}) - €${total}`;
    }
}

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
            });
        });
    });
});
</script>

<?php include 'view/layout/footer.php'; ?>
