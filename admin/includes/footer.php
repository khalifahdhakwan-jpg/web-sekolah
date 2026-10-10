<!-- KONTEN HALAMAN SELESAI DI SINI -->
    </div> <!-- End Content Wrapper -->
</div> <!-- End d-flex -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Custom -->
<script>
    // Logika untuk menampilkan/menyembunyikan sidebar di perangkat mobile (HP)
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Logika sederhana untuk menandai (active) menu sidebar sesuai URL yang sedang dibuka
    const currentLocation = location.pathname.split("/").slice(-1)[0];
    const menuItems = document.querySelectorAll('.sidebar a');
    menuItems.forEach(item => {
        if(item.getAttribute('href') === currentLocation) {
            item.classList.add('active');
        }
    });
</script>

</body>
</html>