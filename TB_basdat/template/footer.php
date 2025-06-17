</div> <!-- Tutup .container dari header -->

<footer style="background: #2c3e50; color: #ecf0f1; text-align: center; padding: 40px 20px; margin-top: 60px; font-size: 14px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 10px;">
            <strong style="font-size: 18px;">TOKAGADGET</strong><br>
            <span>Toko Gadget Terpercaya • Cepat • Aman • Lengkap</span>
        </div>
        <div style="margin: 10px 0;">
            📞 +62 896-5009-0645 &nbsp; | &nbsp; 📧 tokagadget@gmail.com
        </div>
        <hr style="margin: 20px auto; width: 60%; border-color: #7f8c8d;">
        <div>
            &copy; <?= date('Y') ?> <strong>TOKAGADGET</strong>. All rights reserved.
        </div>
    </div>
</footer>

<!-- jQuery CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Bootstrap JS CDN (optional if you're using Bootstrap) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    // Konfirmasi hapus
    function confirmDelete(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
    }

    // Format angka ke Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(angka);
    }

    // Validasi form checkout (contoh)
    $(document).ready(function() {
        $('form[name="checkout"]').submit(function(e) {
            let nama = $('input[name="nama"]').val();
            let telepon = $('input[name="telepon"]').val();
            if (!nama || !telepon) {
                alert('Harap lengkapi semua data!');
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>
