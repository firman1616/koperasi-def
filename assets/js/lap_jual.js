$(document).ready(function() {
    // tableLapTrans();

    $("#detail_trans").hide();
    $("#div-table-lap-trans").hide();

    // Event klik tombol "Lihat"
    $("#preview").click(function() {
        let date_start = $("#date_start").val();
        let date_end = $("#date_end").val();

        if (date_start === "" || date_end === "") {
            alert("Silakan pilih rentang tanggal terlebih dahulu!");
            return;
        }

        $("#detail_trans").show(); // Tampilkan div detail transaksi
        $("#div-table-lap-trans").show(); // Tampilkan div tabel

        // Panggil fungsi untuk load tabel dengan parameter tanggal
        tableLapTrans(date_start, date_end);
    });

    let today = new Date();
    
    // Pastikan timezone lokal digunakan
    let todayStr = today.toLocaleDateString('sv-SE'); // Format YYYY-MM-DD

    // Mendapatkan tanggal 30 hari yang lalu dengan memastikan waktu direset
    let pastDate = new Date();
    pastDate.setDate(today.getDate() - 30);
    pastDate = new Date(pastDate.getFullYear(), pastDate.getMonth(), pastDate.getDate()); // Reset jam

    let pastDateStr = pastDate.toLocaleDateString('sv-SE'); // Format YYYY-MM-DD

    // Menetapkan nilai default pada input date
    document.getElementById("date_end").value = todayStr;
    document.getElementById("date_start").value = pastDateStr;

    $("#export_excel").click(function () {
        window.location.href = BASE_URL + "Laporan/export_excel";
    });

    $(document).on("click", ".btn-detail", function() {
        let idTransaksi = $(this).data("id"); // Ambil ID transaksi
        
        // Debugging: Pastikan ID transaksi terbaca
        console.log("ID Transaksi: ", idTransaksi);
        
        // Kosongkan tabel sebelum diisi ulang
        $("#modalDetailBody").html("");

        $.ajax({
            url: BASE_URL + "Laporan/getDetailTransaksi",
            type: "POST",
            data: {id: idTransaksi},
            dataType: "json",
            success: function(response) {
                console.log("Response dari server:", response); // Debugging
                
                if (response.length > 0) {
                    response.forEach(function(item) {
                        let row = `<tr>
                            <td>${item.kode_barang}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.qty}</td>
                            <td>Rp ${Number(item.harga_beli).toLocaleString('id-ID')}</td>
                        </tr>`;
                        $("#modalDetailBody").append(row);
                    });
                } else {
                    $("#modalDetailBody").html("<tr><td colspan='3' class='text-center'>Tidak ada data</td></tr>");
                }
                $("#detailModal").modal("show");
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error: ", xhr.responseText); // Debugging
                alert("Terjadi kesalahan saat mengambil data.");
            }
        });
    });

});

function tableLapTrans(date_start, date_end) {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapTrans",
        type: "POST",
        data: {date_start: date_start, date_end: date_end},
        success: function (data) {
            $("#div-table-lap-trans").html(data);
            $("#tableLapTrans").DataTable({
                "processing": true,
                "responsive": true,
                "destroy": true, // Hapus instance DataTable sebelum reload
            });
        }
    });
}
