$(document).ready(function () {
    // tableIuran();
    tableKeuangan();
    $("#detail_in").hide();
    $("#detail_out").hide();
    // $("#div-table-lap-pemasukan-keuangan").hide();
    // $("#detail_keuangan").hide();
    // $("#div-table-lap-keuangan").hide();


    $("#preview").click(function () {
        let kategori = $("#kategori").val();
        let date_start = $("#date_start").val();
        let date_end = $("#date_end").val();
        let kategori_utama = $("#kategori_utama").val();

        if (date_start === "" || date_end === "") {
            alert("Silakan lengkapi semua inputan terlebih dahulu!");
            return;
        }

        // if (kategori !== "" || kategori_utama !== "") {
        //     $("#detail_keuangan").hide();
        //     $("#div-table-lap-keuangan").hide();
        // } else {
        //     $("#detail_keuangan").show();
        //     $("#div-table-lap-keuangan").show();
        //     tableKeuangan(date_end);
        // }

        // if (kategori === "11") {
        //     $.ajax({
        //         url: BASE_URL + "Laporan/getTotalTransaksiPOS",
        //         type: "POST",
        //         data: { date_start: date_start, date_end: date_end },
        //         dataType: "json",
        //         success: function (response) {
        //             if (response.status) {
        //                 $("#kategori-text").text("Total " + response.kategori);
        //                 $("#total-transaksi").text(response.total);
        //             } else {
        //                 alert("Data tidak ditemukan!");
        //                 $("#total-transaksi").text("0");
        //             }
        //         }
        //     });
        //     // $("#detail_keuangan").hide();
        //     // $("#div-table-lap-keuangan").hide();
        // }

        // if (kategori === "12") {
        //     $.ajax({
        //         url: BASE_URL + "Laporan/getTotalDeposit",
        //         type: "POST",
        //         data: { date_start: date_start, date_end: date_end },
        //         dataType: "json",
        //         success: function (response) {
        //             if (response.status) {
        //                 $("#kategori-text").text("Total " + response.kategori);
        //                 $("#total-transaksi").text(response.total);
        //             } else {
        //                 alert("Data tidak ditemukan!");
        //                 $("#total-transaksi").text("0");
        //             }
        //         }
        //     });
        //     // $("#detail_keuangan").hide();
        //     // $("#div-table-lap-keuangan").hide();
        // }

        // if (kategori === "3") {
        //     $.ajax({
        //         url: BASE_URL + "Laporan/getTotalIuran",
        //         type: "POST",
        //         data: { date_start: date_start, date_end: date_end },
        //         dataType: "json",
        //         success: function (response) {
        //             if (response.status) {
        //                 $("#kategori-text").text("Total " + response.kategori);
        //                 $("#total-transaksi").text(response.total);
        //             } else {
        //                 alert("Data tidak ditemukan!");
        //                 $("#total-transaksi").text("0");
        //             }
        //         }
        //     });
        //     // $("#detail_keuangan").hide();
        //     // $("#div-table-lap-keuangan").hide();
        // }

        if (kategori_utama === "1") {
            $.ajax({
                url: BASE_URL + "Laporan/getTotalIn",
                type: "POST",
                data: { date_start: date_start, date_end: date_end },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        $("#kategori-text").text("Total " + response.kategori);
                        $("#total-transaksi").text(response.total);
                    } else {
                        alert("Data tidak ditemukan!");
                        $("#total-transaksi").text("0");
                    }
                }
            });
            $("#detail_in").show();
            $("#detail_out").hide();
            tablePemasukanKeuangan(date_start, date_end);
            $("#detail_keuangan").hide();
            $("#div-table-lap-keuangan").hide();
        } else if (kategori_utama === "2") {
            $.ajax({
                url: BASE_URL + "Laporan/getTotalOut",
                type: "POST",
                data: { date_start: date_start, date_end: date_end },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        $("#kategori-text").text("Total " + response.kategori);
                        $("#total-transaksi").text(response.total);
                    } else {
                        alert("Data tidak ditemukan!");
                        $("#total-transaksi").text("0");
                    }
                }
            });
            $("#detail_in").hide();
            $("#detail_out").show();
            tablePengeluaranKeuangan(date_start, date_end);
            $("#detail_keuangan").hide();
            $("#div-table-lap-keuangan").hide();
        } else {
            // Jika kategori_utama kosong, sembunyikan detail_in dan detail_out
            $("#detail_in").hide();
            $("#detail_out").hide();
            $("#detail_keuangan").show();
            $("#div-table-lap-keuangan").show();
        }

        // if (kategori_utama !== "") {
        //     $("#detail_keuangan").hide();
        //     $("#div-table-lap-keuangan").hide();
        // } else {
        //     $("#detail_keuangan").show();
        //     $("#div-table-lap-keuangan").show();
        //     tableKeuangan(date_end);
        // }
    });



    $("#export_excel_keuangan").click(function () {
        let date_end = $("#date_end").val();

        if (date_end === "") {
            alert("Silakan pilih tanggal terlebih dahulu!");
            return;
        }

        // Redirect ke fungsi export di controller dengan parameter date_end
        window.location.href = BASE_URL + "Laporan/export_excel_keuangan?date_end=" + date_end;
    });

    document.getElementById("export_excel_all").addEventListener("click", function() {
        let date_end = $("#date_end").val();

        if (date_end === "") {
            alert("Silakan pilih tanggal terlebih dahulu!");
            return;
        }
        document.getElementById("laporanForm").action = BASE_URL + "Laporan/export_excel_all?date_end=" + date_end;
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

});

function tableKeuangan() {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapKeuangan",
        type: "POST",// Kirim parameter
        success: function (data) {
            $('#div-table-lap-keuangan').html(data);
            // $('#tableKeuangan').DataTable({
            //     "processing": true,
            //     "responsive": true,
            // });
        }
    });
}

function tablePemasukanKeuangan(date_start, date_end) {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapKeuanganMasuk",
        type: "POST",
        data: { date_start: date_start, date_end: date_end },
        success: function (data) {
            $('#div-table-lap-pemasukan-keuangan').html(data);
            $('#tableKeuanganIn').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

function tablePengeluaranKeuangan(date_start, date_end) {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapKeuanganKeluar",
        type: "POST",
        data: { date_start: date_start, date_end: date_end },
        success: function (data) {
            $('#div-table-lap-pengeluaran-keuangan').html(data);
            $('#tableKeuanganOut').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

