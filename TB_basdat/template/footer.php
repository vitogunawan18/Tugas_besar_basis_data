<footer style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%); color: #ecf0f1; text-align: center; padding: 20px; margin-top: 30px; font-size: 13px; box-shadow: 0 -3px 15px rgba(0, 0, 0, 0.15);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 12px;">
            <div style="font-size: 18px; font-weight: 700; margin-bottom: 5px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-mobile-alt" style="font-size: 20px; color: rgba(255, 255, 255, 0.9);"></i>
                <strong>TOKO GADGET</strong>
            </div>
            <span style="font-size: 12px; opacity: 0.8;">Toko Gadget Terpercaya • Cepat • Aman • Lengkap</span>
        </div>
        
        <div style="margin: 15px 0; padding: 10px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
            <div style="display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-phone" style="color: rgba(255, 255, 255, 0.9); font-size: 12px;"></i>
                    <span style="font-size: 12px;">+62 896-5009-0645</span>
                </div>
                <div style="height: 12px; width: 1px; background: rgba(255, 255, 255, 0.3);"></div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-envelope" style="color: rgba(255, 255, 255, 0.9); font-size: 12px;"></i>
                    <span style="font-size: 12px;">tokogadget@gmail.com</span>
                </div>
            </div>
        </div>
        
        <hr style="margin: 12px auto; width: 50%; border: none; height: 1px; background: rgba(255, 255, 255, 0.2);">
        
        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin: 10px 0; flex-wrap: wrap;">
            <div style="padding: 4px 8px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; font-size: 11px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fas fa-store"></i> Point of Sale System
            </div>
            <div style="padding: 4px 8px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; font-size: 11px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fas fa-credit-card"></i> Pembayaran Aman
            </div>
            <div style="padding: 4px 8px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; font-size: 11px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                <i class="fas fa-shipping-fast"></i> Pengiriman Cepat
            </div>
        </div>
        
        <div style="font-size: 11px; opacity: 0.7; margin-top: 10px;">
            &copy; <?= date('Y') ?> <strong>TOKO GADGET</strong>. All rights reserved. | Powered by Modern POS System
        </div>
    </div>
</footer>

<!-- jQuery CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // Konfirmasi hapus dengan style modern
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

    // Validasi form checkout
    $(document).ready(function() {
        // Form checkout validation
        $('form[name="checkout"]').submit(function(e) {
            let nama = $('input[name="nama"]').val();
            let telepon = $('input[name="telepon"]').val();
            if (!nama || !telepon) {
                alert('Harap lengkapi semua data!');
                e.preventDefault();
            }
        });

        // Add loading animation for buttons
        $('.btn-checkout, .quantity-btn, .qty-btn').click(function() {
            var $btn = $(this);
            if (!$btn.prop('disabled')) {
                $btn.css('transform', 'scale(0.95)');
                setTimeout(function() {
                    $btn.css('transform', 'scale(1)');
                }, 150);
            }
        });

        // Auto-hide alerts after 5 seconds
        $('.alert').delay(5000).fadeOut(500);

        // Smooth scroll untuk link internal
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 500);
            }
        });
    });

    // Modern notification system
    function showNotification(message, type = 'info') {
        const notification = $(`
            <div class="modern-notification ${type}" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'error' ? 'linear-gradient(135deg, #e74c3c, #c0392b)' : 'linear-gradient(135deg, #2ecc71, #27ae60)'};
                color: white;
                padding: 12px 16px;
                border-radius: 8px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.3);
                z-index: 9999;
                transform: translateX(400px);
                opacity: 0;
                transition: all 0.3s ease;
                max-width: 280px;
                font-weight: 500;
                font-size: 13px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            ">
                <span style="margin-right: 8px;">${type === 'error' ? '❌' : '✅'}</span>
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.css({
                'transform': 'translateX(0)',
                'opacity': '1'
            });
        }, 100);
        
        setTimeout(() => {
            notification.css({
                'transform': 'translateX(400px)',
                'opacity': '0'
            });
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // Auto-format input telepon
    $('input[type="tel"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.startsWith('62')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            value = '+62' + value.substring(1);
        }
        $(this).val(value);
    });
</script>

<style>
    /* Additional modern styles */
    .modern-notification {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Improved form styles */
    input[type="text"], input[type="email"], input[type="tel"], 
    input[type="password"], input[type="number"], select, textarea {
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        background: rgba(255, 255, 255, 0.9) !important;
    }
    
    input[type="text"]:focus, input[type="email"]:focus, 
    input[type="tel"]:focus, input[type="password"]:focus, 
    input[type="number"]:focus, select:focus, textarea:focus {
        border-color: #3498db !important;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1) !important;
        outline: none !important;
        background: rgba(255, 255, 255, 1) !important;
    }
    
    /* Modern button styles */
    .btn, button {
        border-radius: 8px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        border: none !important;
        cursor: pointer !important;
        font-size: 13px !important;
    }
    
    .btn:hover, button:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2) !important;
    }

    .btn:active, button:active {
        transform: translateY(0) scale(0.98) !important;
    }

    /* Improved table styles */
    table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        font-size: 12px !important;
        letter-spacing: 0.5px !important;
    }

    td {
        vertical-align: middle !important;
        border-bottom: 1px solid #f0f0f0 !important;
    }

    tr:hover {
        background-color: rgba(52, 152, 219, 0.05) !important;
    }

    /* Alert improvements */
    .alert {
        border-radius: 10px !important;
        border: none !important;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1) !important;
        font-weight: 500 !important;
    }

    .alert-success {
        background: linear-gradient(135deg, #2ecc71, #27ae60) !important;
        color: white !important;
    }

    .alert-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
        color: white !important;
    }

    .alert-info {
        background: linear-gradient(135deg, #3498db, #2980b9) !important;
        color: white !important;
    }

    /* Loading spinner */
    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        footer {
            padding: 15px !important;
            font-size: 12px !important;
        }
        
        footer div[style*="gap: 20px"] {
            gap: 15px !important;
        }
        
        footer div[style*="gap: 10px"] {
            gap: 8px !important;
        }
        
        .modern-notification {
            right: 10px !important;
            left: 10px !important;
            max-width: none !important;
        }
    }

    @media (max-width: 480px) {
        footer {
            padding: 12px !important;
        }
        
        footer div[style*="font-size: 18px"] {
            font-size: 16px !important;
        }
        
        footer div[style*="flex-wrap: wrap"] {
            flex-direction: column !important;
            gap: 8px !important;
        }
    }
</style>

</body>
</html>