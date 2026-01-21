<footer class="mt-5">
    <div class="container">
        <hr>
        <p>&copy; <?php echo date('Y'); ?> Edebiyat Bilimleri Dergisi. Tüm hakları saklıdır.</p>
<?php if (isLoggedIn()): ?>
    <a href="http://localhost:8080/submissions/create" class="btn btn-primary mt-2">Makale Gönder</a>
<?php else: ?>
    <a href="/login" class="btn btn-primary mt-2">Makale Gönder</a>
<?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</footer>
</body>
</html>
