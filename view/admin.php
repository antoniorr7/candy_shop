<?php include 'view/layout/header.php'; ?>

<div class="container">
    <div class="page-header">
        <a href="index.php" class="back-btn">← Volver al TPV</a>
        <h2>⚙️ Administración</h2>
        <span class="info-icon" onclick="toggleInfo(this);">ℹ️</span>
        <div class="info-tooltip">
            Panel de administración para gestionar productos, ver ventas 
            y controlar el inventario.
        </div>
    </div>

    <!-- Resumen de Ventas -->
    <div class="admin-section">
        <h3>📊 Resumen de Ventas</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Hoy</h4>
                <p class="stat-value">€<?php echo number_format($daily_sales['daily_total'] ?? 0, 2); ?></p>
                <p class="stat-detail"><?php echo $daily_sales['sales_count'] ?? 0; ?> ventas</p>
            </div>
            <div class="stat-card">
                <h4>Este Mes</h4>
                <p class="stat-value">€<?php echo number_format($monthly_sales['monthly_total'] ?? 0, 2); ?></p>
                <p class="stat-detail"><?php echo $monthly_sales['sales_count'] ?? 0; ?> ventas</p>
            </div>
        </div>
    </div>

    <!-- Ventas Recientes -->
    <div class="admin-section">
        <h3>🕒 Ventas Recientes</h3>
        <div class="recent-sales">
            <?php while ($sale = $recent_sales->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="sale-item">
                <span class="sale-date"><?php echo date('d/m H:i', strtotime($sale['sale_date'])); ?></span>
                <span class="sale-total">€<?php echo number_format($sale['total'], 2); ?></span>
                <span class="sale-items"><?php echo $sale['items_count']; ?> artículos</span>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Gestión de Productos -->
    <div class="admin-section">
        <h3>📦 Gestión de Productos</h3>
        <span class="info-icon" onclick="toggleInfo(this);">ℹ️</span>
        <div class="info-tooltip">
            Aquí puedes agregar, editar o eliminar productos. Los productos 
            "por peso" se venden en cantidades variables.
        </div>
        
        <!-- Formulario para Agregar Producto -->
        <div class="add-product-form">
            <h4>Agregar Nuevo Producto</h4>
            <form method="POST" action="index.php?action=add_product">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Nombre del producto" required>
                    <select name="category_id" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="1">Caramelos</option>
                        <option value="2">Artículos Sueltos</option>
                        <option value="3">Nevera</option>
                    </select>
                </div>
                <div class="form-row">
                    <input type="number" name="price" placeholder="Precio" step="0.01" min="0" required>
                    <input type="number" name="stock" placeholder="Stock inicial" min="0" required>
                </div>
                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_weight_based">
                        Producto por peso
                        <span class="info-icon" onclick="toggleInfo(this);">ℹ️</span>
                        <div class="info-tooltip">
                            Marca esta opción si el producto se vende por peso 
                            (como los caramelos a granel).
                        </div>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>

        <!-- Lista de Productos -->
        <div class="products-list">
            <h4>Productos Existentes</h4>
            <div class="products-table">
                <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="product-row">
                    <div class="product-info">
                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                        <span class="category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                        <span class="price">€<?php echo number_format($product['price'], 2); ?></span>
                        <span class="stock">Stock: <?php echo $product['stock']; ?></span>
                        <?php if ($product['is_weight_based']): ?>
                            <span class="weight-badge">Por peso</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-actions">
                        <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                                class="btn btn-small">Editar</button>
                        <a href="index.php?action=delete_product&id=<?php echo $product['id']; ?>" 
                           class="btn btn-small btn-danger"
                           onclick="return confirmDelete('¿Eliminar este producto?');">Eliminar</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Producto -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h3>Editar Producto</h3>
        <form method="POST" action="index.php?action=update_product">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-row">
                <input type="text" name="name" id="edit_name" placeholder="Nombre del producto" required>
                <select name="category_id" id="edit_category" required>
                    <option value="1">Caramelos</option>
                    <option value="2">Artículos Sueltos</option>
                    <option value="3">Nevera</option>
                </select>
            </div>
            <div class="form-row">
                <input type="number" name="price" id="edit_price" placeholder="Precio" step="0.01" min="0" required>
                <input type="number" name="stock" id="edit_stock" placeholder="Stock" min="0" required>
            </div>
            <div class="form-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_weight_based" id="edit_weight">
                    Producto por peso
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProduct(product) {
    document.getElementById('edit_id').value = product.id;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_category').value = product.category_id;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_stock').value = product.stock;
    document.getElementById('edit_weight').checked = product.is_weight_based == 1;
    
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php include 'view/layout/footer.php'; ?>
