$(document).ready(function () {
    setTanggalHariIni()
    tablePengeluaranLain();
    $('#id').val('');
    $('#pengeluaran').trigger("reset");
    setTanggalHariIni()

    $('#save-data').click(function (e) {
        e.preventDefault();
        let id = $('#id').val();
        let kategori = $('#kategori').val();
        let sumberdana = $('#sumberdana').val();
        let keterangan = $('#keterangan').val();
        let nominal = parseFloat($('#nominal').val());
        let message = id ? "Data Berhasil Diupdate!" : "Data Berhasil Ditambahkan!";
    
        // Validasi input
        if (!kategori || !sumberdana || isNaN(nominal) || nominal <= 0 || !keterangan) {
            alert("Harap isi kategori, sumber dana, nominal dan Keterangan dengan benar.");
            return;
        }
    
        // Konfirmasi sebelum menyimpan
        if (!confirm("Apakah Anda yakin ingin menyimpan data ini?")) {
            return;
        }
    
        $.ajax({
            data: $('#pengeluaran').serialize(),
            url: BASE_URL + "PengeluaranLain/store",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    
                    // Reset Form
                    $('#pengeluaran').trigger("reset");
                    setTanggalHariIni()
                    $('#id').val('');
                    $('#kategori').val('').trigger('change');
                    $('#saldoTersedia').text('Rp. 0,-');
                    tablePengeluaranLain(); // Reload table
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                alert("Terjadi kesalahan, coba lagi.");
            }
        });
    });
    
    

     $('body').on('click','.edit',function (e) {
        var id = $(this).data('id');
        $.ajax({
            url: BASE_URL + "PengeluaranLain/vedit/" + id,
            type: 'GET',
            dataType : 'json',            
            success: function (data) {
                console.log(data);
                $('#id').val(id);
                $('#kategori').val(data.kategori_id).trigger('change');
                $('#sumberdana').val(data.sumber_dana_id).trigger('change');
                $('#nominal').val(data.nominal);
                $('#keterangan').val(data.keterangan);
            }
        })
    })

    let saldoTersedia = 0; // Variabel untuk menyimpan saldo

    // Ketika memilih sumber dana, ambil saldo dari server
    $('#sumberdana').change(function () {
        let sumberdana = $(this).val();
    
        if (sumberdana) {
            $.ajax({
                url: BASE_URL + "PengeluaranLain/getSaldo", // Endpoint untuk mendapatkan saldo
                type: "POST",
                data: { sumberdana: sumberdana },
                dataType: "json",
                success: function (response) {
                    console.log(response); // Debugging
    
                    if (response.saldo !== undefined) {
                        saldoTersedia = response.saldo;
    
                        // Format angka dengan titik sebagai pemisah ribuan
                        let saldoFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(saldoTersedia);
    
                        $('#saldoTersedia').text(saldoFormatted);
                    } else {
                        $('#saldoTersedia').text('Rp. 0,-');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        } else {
            $('#saldoTersedia').text('Rp. 0,-'); // Jika tidak ada sumber dana, tampilkan saldo 0
        }
    });
    

    // Validasi input nominal
    $('#nominal').on('input', function () {
        let nominal = $(this).val();
        if (parseInt(nominal) > saldoTersedia) {
            alert("Saldo Tidak Mencukupi, Ganti Sumber Dana!");
            $(this).val(saldoTersedia); // Batasi nominal ke saldo maksimal
        }
    });

});

function tablePengeluaranLain() {
    $.ajax({
        url: BASE_URL + "PengeluaranLain/tablePengeluaranLain",
        type: "POST",
        success: function (data) {
            $('#div-table-pengeluaran-lain').html(data);
            $('#tablePengeluaranLain').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

$(document).on('click', '.delete-btn', function (event) {
    event.preventDefault(); // Mencegah link langsung dijalankan

    var url = $(this).attr('href'); // Ambil URL dari href
    let sumberdana = $('#sumberdana').val(); // Ambil sumber dana yang sedang dipilih

    // Konfirmasi sebelum menghapus
    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        $.ajax({
            url: url,
            type: "GET",
            success: function () {
                alert("Data berhasil dihapus!");

                tablePengeluaranLain(); // Reload tabel
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan, coba lagi.");
            }
        });
    }
});

function setTanggalHariIni() {
    $('#tgl_pengeluaran').val(new Date().toISOString().split('T')[0]);
}

document.addEventListener("DOMContentLoaded", function () {
    let inputNominal = document.getElementById("nominal");
    let inputHidden = document.getElementById("nominal_raw");

    inputNominal.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Hanya angka
        let formattedValue = formatRupiah(value);
        e.target.value = formattedValue;
        inputHidden.value = value; // Simpan nilai asli tanpa format
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});


