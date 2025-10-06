$(document).ready(function () {
    tablePeserta();
    tableIuran();
    bindDepositButtonClick();
});

// Load tabel peserta
function tablePeserta() {
    $.ajax({
        url: BASE_URL + "Peserta/tablePeserta",
        type: "POST",
        success: function (data) {
            $('#div-table-peserta').html(data);
            $('#tablePeserta').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

// Load tabel iuran
function tableIuran() {
    $.ajax({
        url: BASE_URL + "Peserta/tableIuran",
        type: "POST",
        success: function (data) {
            $('#div-table-iuran').html(data);
            $('#tableIuran').DataTable({
                "processing": true,
                "responsive": true,
                "ordering": true,
                "paging": false,
                "scrollY": "500px", // Scroll vertikal dengan tinggi tetap 400px
                "scrollCollapse": true,
            });

            // Pastikan event handler di-bind ulang setelah tabel iuran di-load
            bindIuranButtonClick();
        }
    });
}

// Fungsi untuk menampilkan modal pembayaran
function showModal(anggotaId, periode) {
    $("#periode").val(periode).data("anggota-id", anggotaId);

    let now = new Date();
    let formattedDate = now.toISOString().slice(0, 10); // Format YYYY-MM-DD
    $("#tanggal").val(formattedDate);

    let myModal = new bootstrap.Modal(document.getElementById('iuranModal'));
    myModal.show();
}

// Fungsi untuk menangani klik tombol bayar iuran
function bindIuranButtonClick() {
    $(document).off("click", ".iuran-btn").on("click", ".iuran-btn", function () {
        var anggotaId = $(this).data("id");
        var periode = $(this).data("periode");

        showModal(anggotaId, periode);
    });

    $(document).off("click", ".iuran").on("click", ".iuran", function () {
        var anggotaId = $("#periode").data("anggota-id"); // Ambil anggota_id dari modal
        var periode = $("#periode").val();
        var date = $("#tanggal").val();

        if (!anggotaId) {
            alert("Data anggota tidak ditemukan!");
            return;
        }

        if (!periode || !date) {
            alert("Periode dan tanggal harus diisi!");
            return;
        }

        var confirmAction = confirm("Apakah Anda yakin ingin membayar iuran untuk periode " + periode + "?");
        if (!confirmAction) {
            return;
        }

        $.ajax({
            url: BASE_URL + "Peserta/update_iuran",
            type: "POST",
            data: { anggota_id: anggotaId, periode: periode, date: date },
            dataType: "json",
            success: function (response) {
                if (response.status == "success") {
                    alert("Iuran periode " + periode + " berhasil dibayar!");

                    $("#iuranModal").modal("hide");
                    location.reload();
                    tableIuran();
                } else {
                    alert("Gagal memperbarui iuran.");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
}

function bindDepositButtonClick() {
    // Ketika tombol "Deposit" diklik, simpan ID anggota di modal
    $(document).on("click", ".deposit-btn", function () {
        var anggota_id = $(this).data("id");
        $("#depositModal").data("id", anggota_id); // Simpan di modal
    });

    // Saat tombol "Submit" diklik, ambil ID anggota dari modal
    $(document).off("click", "#submitDeposit").on("click", "#submitDeposit", function () {
        var anggota_id = $("#depositModal").data("id"); // Ambil ID yang tersimpan di modal
        var nominal = $('#depositAmount').val();
        var date = new Date().toISOString().slice(0, 10); // Format YYYY-MM-DD

        if (!anggota_id) {
            alert("Data anggota tidak ditemukan!");
            return;
        }

        var confirmAction = confirm("Apakah Anda yakin ingin menyimpan deposit?");
        if (!confirmAction) {
            return;
        }

        $.ajax({
            url: BASE_URL + "Peserta/deposit",
            type: "POST",
            data: { anggota_id: anggota_id, nominal: nominal, date: date, status: 1 },
            dataType: "json",
            success: function (response) {
                if (response.status == "success") {
                    alert("Deposit berhasil disimpan!");
                    $('#depositModal').modal('hide');
                    location.reload();
                } else {
                    alert("Gagal menyimpan deposit.");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
}


// Panggil fungsi untuk binding event setelah halaman dimuat
bindIuranButtonClick();
bindDepositButtonClick();




// Event klik tombol "iuran"
// $(document).on('click', '.iuran-btn', function() {
//     var anggota_id = $(this).data('id'); // Ambil ID anggota dari atribut data-id
//     var confirmPayment = confirm("Apakah sudah bayar?");

//     if (confirmPayment) {
//         $.ajax({
//             url: BASE_URL + "Peserta/simpanIuran",
//             type: "POST",
//             data: {
//                 anggota_id: anggota_id
//             },
//             success: function(response) {
//                 alert("Iuran sudah dibayar"); // Menampilkan alert setelah klik
//                 setTimeout(tableIuran, 100); // Refresh otomatis setelah klik
//             },
//             error: function() {
//                 alert("Terjadi kesalahan, coba lagi!");
//             }
//         });
//     } else {
//         alert("Pembayaran belum dikonfirmasi.");
//     }
// });


$(document).on('click', '.delete-btn', function (event) {
    event.preventDefault(); // Mencegah link langsung dijalankan

    var url = $(this).attr('href'); // Ambil URL dari href

    // Konfirmasi sebelum menghapus
    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        window.location.href = url; // Jika dikonfirmasi, jalankan URL
    }
});
