$(document).ready(function () {
    tableTransaksi();
    tableTempo();
    $('#id').val('');
    $('#modulForm').trigger("reset");
    $('.transelect2').select2();

    // Tombol Bayar ditekan
    $("#bayarBtn").click(function () {
        $("#tanggal").val(getCurrentDateTime());
        $("#modalPembayaran").modal("show");
    });

    function cekInput() {
        var barcode = $("#barcode").val().trim();
        var jumlah = $("#jumlah").val().trim();
        $("#tambahBtn").prop("disabled", barcode === "" || jumlah === "" || parseInt(jumlah) <= 0);
    }

    function cekTabelTransaksi() {
        $("#bayarBtn").prop("disabled", $("#tableTransaksi tbody tr").length === 0);
        hitungTotalHarga();
    }

    function hitungTotalHarga() {
        var total = 0;
        $("#tableTransaksi tbody tr").each(function () {
            var hargaText = $(this).find("td:nth-child(3)").text(); // Kolom Harga
            var qty = parseInt($(this).find("td:nth-child(4)").text()); // Kolom Qty

            // Ambil angka dari format "Rp. xxx.xxx,yy"
            var harga = parseFloat(hargaText.replace(/Rp. |,/g, "").replace(".", ""));
            total += harga * qty;
        });

        $(".total-harga").text(`Rp. ${total.toLocaleString("id-ID")}`);
    }

    $("#barcode").change(function () {
        var barcode = $(this).val();
        if (barcode !== "") {
            $.ajax({
                url: BASE_URL + "Transaksi/get_barang",
                type: "GET",
                data: { barcode: barcode },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        $("#tambahBtn").data("barang", data);
                        $("#qty-tersedia").text(data.qty);
                    }
                }
            });
        }
        cekInput();
    });

    $("#jumlah").on("keyup change", cekInput);

    $("#tambahBtn").click(function () {
        var jumlah = parseInt($("#jumlah").val());
        var barang = $(this).data("barang");

        if (!barang) {
            alert("Barang tidak ditemukan!");
            return;
        }

        if (jumlah > barang.qty) {
            alert("Stok tidak mencukupi!");
            return;
        }

        var newRow = `<tr>
            <td>${barang.kode_barang}</td>
            <td>${barang.nama_barang}</td>
            <td>Rp. ${parseFloat(barang.harga_jual).toLocaleString()}</td>
            <td>${jumlah}</td>
            <td><button class="btn btn-danger btn-sm removeRow">Hapus</button></td>
        </tr>`;

        $("#tableTransaksi tbody").append(newRow);
        $("#barcode").val("").trigger("change");
        $("#jumlah").val("");
        $("#qty-tersedia").text("-");
        $("#tambahBtn").prop("disabled", true);

        cekTabelTransaksi();
    });

    $(document).on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
        cekTabelTransaksi();
    });

    function loadBarang() {
        $.ajax({
            url: BASE_URL + "Transaksi/get_all_barang",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("#barcode").empty().append('<option value="">Pilih Barang</option>');
                $.each(data, function (index, barang) {
                    $("#barcode").append(`<option value="${barang.kode_barang}">${barang.kode_barang} - ${barang.nama_barang}</option>`);
                });
            }
        });
    }

    loadBarang();
    hitungTotalHarga();

    function getCurrentDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    function loadAnggota() {
        $.ajax({
            url: BASE_URL + "Transaksi/get_anggota",
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log("Data diterima:", data); // Debugging

                $("#anggota").empty().append('<option value="">Pilih Anggota</option>');

                $.each(data, function (index, item) {
                    $("#anggota").append(`<option value="${item.id}">${item.no_agt} - ${item.name}</option>`);
                });

                // Inisialisasi Select2 setelah data dimuat
                $("#anggota").select2({
                    dropdownParent: $("#modalPembayaran"),
                    width: "100%",
                    placeholder: "Pilih Anggota",
                    allowClear: true
                });

                // Event listener untuk menampilkan form tambahan jika ID = 117
                $("#anggota").on("change", function () {
                    var selectedID = $(this).val();

                    if (selectedID === "117") {
                        $("#formTambahan").show();  // Tampilkan form tambahan
                        // $("#formTambahan").prop("required", true);
                    } else {
                        $("#formTambahan").hide();  // Sembunyikan jika bukan ID 117
                        // $("#formTambahan").prop("required", false); // Hapus kewajiban input
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    // **Mengambil total harga saat modal pembayaran ditampilkan**
    $("#modalPembayaran").on("shown.bs.modal", function () {
        var totalHarga = $(".total-harga").text().replace(/[^\d]/g, ""); // Ambil angka tanpa karakter non-digit
        totalHarga = parseFloat(totalHarga) || 0; // Konversi ke angka
        loadAnggota();
        // Format ulang dalam bentuk rupiah yang benar
        $("#total_bayar").text("Rp. " + totalHarga.toLocaleString("id-ID"));
        $("#total_bayar").data("total", totalHarga);
        $("#total_kembalian").text("Rp. 0");
    });

    // **Menghitung total bayar & kembalian saat input berubah**
    $("#uang_dibayarkan, #diskon").on("input", function () {
        var totalHarga = parseFloat($("#total_bayar").data("total")) || 0;
        var uangDibayarkan = parseFloat($("#uang_dibayarkan").val()) || 0;
        var diskon = parseFloat($("#diskon").val()) || 0;
        var totalSetelahDiskon = totalHarga - (totalHarga * (diskon / 100));
        var kembalian = uangDibayarkan - totalSetelahDiskon;


        $("#total_bayar").text("Rp. " + totalSetelahDiskon.toLocaleString("id-ID"));
        $("#total_kembalian").text("Rp. " + (kembalian >= 0 ? kembalian.toLocaleString("id-ID") : "0"));
    });

    // **Proses Pembayaran**
    $("#prosesBayar").click(function () {
        var kd_trans = $("#kd_trans").val(); // Ambil nilai kd_trans dari index.php
        var tanggal = $("#tanggal").val();
        var uangDibayarkan = parseFloat($("#uang_dibayarkan").val());
        var diskon = parseFloat($("#diskon").val()) || 0;
        var totalHarga = parseFloat($("#total_bayar").data("total")) || 0;
        var anggotaID = $("#anggota").val();
        var extraFieldValue = $("#extraField").val();
        var metodeBayar = $("#metode_bayar").val();
        var id_user = $("#id_user").val();

        // if (!tanggal || !kd_trans || uangDibayarkan <= 0) {
        //     alert("Silakan isi semua data pembayaran!");
        //     return;
        // }

        if (!anggotaID) {
            alert("Silakan pilih anggota sebelum melakukan pembayaran!");
            $("#anggota").focus();
            return;
        }

        if (anggotaID === "117" && extraFieldValue === "") {
            alert("Silakan isi 'Pelanggan Lainnya' sebelum melanjutkan pembayaran!");
            $("#extraField").focus();
            return;
        }

        if (!tanggal || !kd_trans) {
            alert("Silakan isi semua data pembayaran!");
            return;
        }

        // Jika metode pembayaran adalah "Cash", pastikan uang dibayarkan cukup
        if (metodeBayar == "1" && uangDibayarkan <= 0) {
            alert("Silakan isi jumlah uang yang dibayarkan!");
            return;
        }

        // Jika metode "Tempo", pastikan uang dibayarkan tetap 0
        if (metodeBayar == "2") {
            uangDibayarkan = 0; // Pastikan tetap 0
        }

        var totalSetelahDiskon = totalHarga - (totalHarga * (diskon / 100));
        var kembalian = uangDibayarkan - totalSetelahDiskon;

        // if (uangDibayarkan < totalSetelahDiskon) {
        //     alert("Uang yang dibayarkan kurang!");
        //     return;
        // }
        if (metodeBayar == "1" && uangDibayarkan < totalSetelahDiskon) {
            alert("Uang yang dibayarkan kurang!");
            return;
        }

        var barang = [];
        $("#tableTransaksi tbody tr").each(function () {
            barang.push({
                barcode: $(this).find("td:nth-child(1)").text(),
                jumlah: parseInt($(this).find("td:nth-child(4)").text()),
                harga: parseFloat($(this).find("td:nth-child(3)").text().replace(/Rp. |,/g, "").replace(".", ""))
            });
        });

        $.ajax({
            url: BASE_URL + "Transaksi/proses_pembayaran",
            type: "POST",
            data: {
                kd_trans: kd_trans,
                tanggal: tanggal,
                uang_dibayarkan: uangDibayarkan,
                diskon: diskon,
                total_setelah_diskon: totalSetelahDiskon,
                anggota_id: anggotaID,
                extraField: extraFieldValue, // Perbaikan nama agar sesuai dengan Controller
                metode_bayar: metodeBayar,
                barang: barang,
                id_user: id_user
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "duplicate") {
                    Swal.fire({
                        icon: "warning",
                        title: "Kode Transaksi Sudah Ada",
                        text: "Kode transaksi ini sudah digunakan. Silakan gunakan kode lain.",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                if (response.status === "success") {
                    alert("Pembayaran berhasil! Kembalian: Rp. " + kembalian.toLocaleString("id-ID"));
                    location.reload();
                } else {
                    alert("Terjadi kesalahan saat memproses pembayaran!");
                }
            },
            error: function () {
                alert("Terjadi kesalahan dalam pengiriman data!");
            }
        });


        $("#modalPembayaran").modal("hide");
    });

    $("#bayarCetakBtn").click(function () {
        var kd_trans = $("#kd_trans").val();
        var tanggal = $("#tanggal").val();
        var uangDibayarkan = parseFloat($("#uang_dibayarkan").val());
        var diskon = parseFloat($("#diskon").val()) || 0;
        var totalHarga = parseFloat($("#total_bayar").data("total")) || 0;
        var anggotaID = $("#anggota").val();
        var id_akhir = $("#id_akhir").val(); // Ambil ID transaksi dari form
        var id_user = $("#id_user").val();
        var extraFieldValue = $("#extraField").val();
        var metodeBayar = $("#metode_bayar").val();

        if (!anggotaID) {
            alert("Silakan pilih anggota sebelum melakukan pembayaran!");
            $("#anggota").focus();
            return;
        }

        if (anggotaID === "117" && extraFieldValue === "") {
            alert("Silakan isi 'Pelanggan Lainnya' sebelum melanjutkan pembayaran!");
            $("#extraField").focus();
            return;
        }

        if (!tanggal || !kd_trans || uangDibayarkan <= 0) {
            alert("Silakan isi semua data pembayaran!");
            return;
        }

        var totalSetelahDiskon = totalHarga - (totalHarga * (diskon / 100));
        var kembalian = uangDibayarkan - totalSetelahDiskon;

        if (uangDibayarkan < totalSetelahDiskon) {
            alert("Uang yang dibayarkan kurang!");
            return;
        }

        var barang = [];
        $("#tableTransaksi tbody tr").each(function () {
            barang.push({
                barcode: $(this).find("td:nth-child(1)").text(),
                jumlah: parseInt($(this).find("td:nth-child(4)").text()),
                harga: parseFloat($(this).find("td:nth-child(3)").text().replace(/Rp. |,/g, "").replace(".", ""))
            });
        });

        $.ajax({
            url: BASE_URL + "Transaksi/proses_pembayaran",
            type: "POST",
            data: {
                kd_trans: kd_trans,
                tanggal: tanggal,
                uang_dibayarkan: uangDibayarkan,
                diskon: diskon,
                total_setelah_diskon: totalSetelahDiskon,
                anggota_id: anggotaID,
                barang: barang,
                id_user: id_user,
                extraField: extraFieldValue,
                metode_bayar: metodeBayar,
            },
            dataType: "json",
            success: function (response) {
                console.log("Response dari server:", response); // Debugging
                if (response.status === "duplicate") {
                    Swal.fire({
                        icon: "warning",
                        title: "Kode Transaksi Sudah Ada",
                        text: "Kode transaksi ini sudah digunakan. Silakan gunakan kode lain.",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                alert("Pembayaran berhasil! Kembalian: Rp. " + kembalian.toLocaleString("id-ID"));
                if (id_akhir) { // Cek apakah id_akhir tersedia di form
                    var printWindow = window.open(BASE_URL + "Transaksi/cetak_struk/" + id_akhir, "_blank");

                    // Tunggu hingga halaman cetak terbuka, lalu jalankan autoPrint
                    if (printWindow) {
                        printWindow.onload = function () {
                            printWindow.print();
                            setTimeout(() => {
                                printWindow.close(); // Tutup tab setelah cetak selesai
                            }, 1000);
                        };
                    } else {
                        alert("Pop-up terblokir! Izinkan pop-up untuk mencetak struk.");
                    }
                } else {
                    alert("ID transaksi tidak ditemukan!");
                }
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                console.log("Response Text:", xhr.responseText);
                alert("Terjadi kesalahan dalam pengiriman data!");
            }
        });

        $("#modalPembayaran").modal("hide");
    });

    $("#metode_bayar").change(function () {
        var metode = $(this).val();
        if (metode == "2") {
            $("#uang_dibayarkan").val(0).prop("readonly", true);
            $("#bayarCetakBtn").prop("disabled", true);
        } else {
            $("#uang_dibayarkan").val("").prop("readonly", false);
        }
    });

    // section tempo
    $(document).on("click", ".openModalTempo", function () {
        var noTransaksi = $(this).data("transaksi");
        var id = $(this).data("id");
        var nominal = $(this).data("nominal");

        $("#id_transaksi").val(id);
        $("#noTransaksi").val(noTransaksi);
        $("#nominalTransaksi").val(nominal);

        $("#modalDetailTempo").modal("show");
    });

    $(document).on("input", "#nominalBayar", function () {
        var nominalTagihan = parseFloat($("#nominalTransaksi").val().replace(/Rp. |,/g, "")) || 0;
        var nominalBayar = parseFloat($(this).val().replace(/Rp. |,/g, "")) || 0;

        var nominalKembali = nominalBayar - nominalTagihan;

        $("#nominalKembali").val(nominalKembali);
    });

    $(document).on("click", "#btnBayar", function () {
        var id_transaksi = $("#id_transaksi").val(); // Ambil ID transaksi dari input
        var uang_bayar = parseFloat($("#nominalBayar").val().replace(/Rp. |,/g, "")) || 0;
        var uang_kembali = parseFloat($("#nominalKembali").val().replace(/Rp. |,/g, "")) || 0;

        console.log("ID Transaksi:", id_transaksi); // Debugging
        console.log("Uang Bayar:", uang_bayar);
        console.log("Uang Kembali:", uang_kembali);

        if (!id_transaksi) {
            alert("ID Transaksi tidak ditemukan!");
            return;
        }

        $.ajax({
            url: BASE_URL + "Transaksi/updatePembayaran",
            type: "POST",
            data: {
                id_transaksi: id_transaksi,
                uang_bayar: uang_bayar,
                uang_kembali: uang_kembali
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    $("#modalDetailTempo").modal("hide");
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", error);
                console.log("Response:", xhr.responseText);
                alert("Terjadi kesalahan dalam proses pembayaran!");
            }
        });
    });

    $("#refreshKode").click(function () {
        $.ajax({
            url: BASE_URL + "Transaksi/generate_kode", // arahkan ke controller
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.kode) {
                    $("#kd_trans").val(response.kode);

                    Swal.fire({
                        icon: "success",
                        title: "Kode Diperbarui",
                        text: "Kode transaksi sekarang: " + response.kode,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Membuat Kode",
                        text: "Terjadi kesalahan saat membuat kode transaksi baru."
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Terhubung",
                    text: "Tidak dapat menghubungi server untuk generate kode."
                });
            }
        });
    });
});

function autofill() {
    var kode = $('#barcode').val();
    // console.log(kode);
    $.ajax({
        url: BASE_URL + "Transaksi/cari",
        //  url:"<?php echo base_url();?>admin/Mutasi/cari",
        data: "&kode=" + kode,
        success: function (data) {
            var hasil = JSON.parse(data);

            $('#qty-tersedia').text(hasil.qty);
            // $("#nama_barang").val(hasil.nama_barang);
            // $("#harga_jual").val(hasil.harga_jual);
        },
    });
}

function tableTransaksi() {
    $.ajax({
        url: BASE_URL + "Transaksi/tableTransaksi",
        type: "POST",
        success: function (data) {
            $('#div-table-transaksi').html(data);
            // $('#tableTransaksi').DataTable({
            //     "processing": true,
            //     "responsive": true,
            // });
        }
    });
}

function tableTempo() {
    $.ajax({
        url: BASE_URL + "Transaksi/tableTempo",
        type: "POST",
        success: function (data) {
            $('#div-table-tempo').html(data);
            $('#tableTempo').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

