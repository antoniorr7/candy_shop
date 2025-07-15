<?php 
require_once 'model/Cart.php';
include 'view/layout/header.php'; 
?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← VOLVER</a>
        <h2>TICKET DE VENTA</h2>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <div class="empty-ticket">
            <p>TICKET VACÍO</p>
            <a href="index.php" class="btn-primary">COMENZAR VENTA</a>
        </div>
    <?php else: ?>
        
        <!-- Lista de productos -->
        <div class="ticket-items">
            <?php foreach ($cart_items as $item): ?>
            <div class="ticket-item">
                <div class="item-details">
                    <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="item-line">
                        <span><?php echo $item['quantity']; ?> x €<?php echo number_format($item['price'], 2); ?></span>
                        <span class="item-total">€<?php echo number_format($item['quantity'] * $item['price'], 2); ?></span>
                    </div>
                </div>
                <a href="index.php?action=remove_from_cart&product_id=<?php echo $item['product_id']; ?>" 
                   class="btn-remove">X</a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Total -->
        <div class="ticket-total">
            <div class="total-line">
                <span>TOTAL:</span>
                <span class="total-amount">€<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="ticket-actions">
            <button onclick="showCalculator()" class="btn-primary">COBRAR</button>
            <a href="index.php?action=clear_cart" class="btn-secondary">CANCELAR</a>
        </div>

    <?php endif; ?>
</div>

<!-- Calculadora de cambio -->
<div id="calculator-modal" class="modal" style="display: none;">
    <div class="calculator">
        <h3>CALCULADORA DE CAMBIO</h3>
        
        <div class="calc-line">
            <span>TOTAL A COBRAR:</span>
            <span class="calc-amount">€<?php echo number_format($total, 2); ?></span>
        </div>
        
        <div class="calc-input">
            <label>DINERO RECIBIDO:</label>
            <input type="number" id="money-received" step="0.01" min="0" placeholder="0.00">
        </div>
        
        <div class="calc-line">
            <span>CAMBIO A DEVOLVER:</span>
            <span id="change-amount" class="calc-amount">€0.00</span>
        </div>
        
        <div class="calc-actions">
            <button onclick="hideCalculator()" class="btn-secondary">CANCELAR</button>
            <button onclick="confirmSale()" class="btn-primary" id="confirm-btn" disabled>CONFIRMAR VENTA</button>
        </div>
    </div>
</div>

<script>
const total = <?php echo $total; ?>;

function showCalculator() {
    document.getElementById('calculator-modal').style.display = 'flex';
    document.getElementById('money-received').focus();
}

function hideCalculator() {
    document.getElementById('calculator-modal').style.display = 'none';
    document.getElementById('money-received').value = '';
    document.getElementById('change-amount').textContent = '€0.00';
    document.getElementById('confirm-btn').disabled = true;
}

function confirmSale() {
    const received = parseFloat(document.getElementById('money-received').value);
    if (received >= total) {
        window.location.href = 'index.php?action=checkout';
    }
}

document.getElementById('money-received').addEventListener('input', function() {
    const received = parseFloat(this.value) || 0;
    const change = received - total;
    
    document.getElementById('change-amount').textContent = '€' + change.toFixed(2);
    document.getElementById('confirm-btn').disabled = received < total;
    
    if (change < 0) {
        document.getElementById('change-amount').style.color = '#dc3545';
    } else {
        document.getElementById('change-amount').style.color = '#28a745';
    }
});

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideCalculator();
    }
});
</script>

<?php include 'view/layout/footer.php'; ?>
