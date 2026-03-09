</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('[data-confirm]').forEach(function (element) {
    element.addEventListener('click', function (event) {
        if (!confirm(element.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });
});
</script>
</body>
</html>
