<?php 
require_once 'model/Cart.php';
include 'view/layout/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← VOLVER</a>
        <h2>ARTICULOS</h2>
    </div>

    <div id="message-area"></div>

    <div class="products-list">
        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="product-item">
            <div class="product-info">
                <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="product-price">€<?php echo number_format($row['price'], 2); ?></div>
            </div>
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
        <?php endwhile; ?>
    </div>
</div>

<script>
function changeQty(btn, change) {
    const input = btn.parentNode.querySelector('input[name="quantity"]');
    const newValue = Math.max(1, parseInt(input.value) + change);
    input.value = newValue;
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

function showMessage(message, type) {
    const messageArea = document.getElementById('message-area');
    const msg = document.createElement('div');
    msg.className = 'message ' + type;
    msg.textContent = message;
    messageArea.appendChild(msg);
    setTimeout(() => {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 350);
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
