</main>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Tienda de Chuches TPV</p>
        </div>
    </footer>

    <script>
        // Función para mostrar/ocultar información de ayuda
        function toggleInfo(element) {
            const info = element.nextElementSibling;
            if (info && info.classList.contains('info-tooltip')) {
                info.style.display = info.style.display === 'block' ? 'none' : 'block';
            }
        }

        // Función para confirmar eliminación
        function confirmDelete(message) {
            return confirm(message || '¿Estás seguro de que quieres eliminar este elemento?');
        }

        // Auto-ocultar mensajes después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
