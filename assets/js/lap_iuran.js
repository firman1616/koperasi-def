$(document).ready(function () {
    // tableIuran();
    $("#detail_iuran").hide();
    $("#div-table-lap-iuran").hide();

    $("#preview").click(function () {
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();

        if (bulan === "" || tahun === "") {
            alert("Silakan pilih periode terlebih dahulu!");
            return;
        }

        $("#detail_iuran").show(); // Tampilkan div detail transaksi
        $("#div-table-lap-iuran").show(); // Tampilkan div tabel

        // Panggil fungsi untuk load tabel dengan parameter tanggal
        tableLapIuran(bulan, tahun);
    });

    $("#export_excel_iuran").click(function () {
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();

        if (bulan === "" || tahun === "") {
            alert("Silakan pilih periode terlebih dahulu!");
            return;
        }

        // Redirect ke fungsi export di controller dengan parameter bulan & tahun
        window.location.href = BASE_URL + "Laporan/export_excel_iuran?bulan=" + bulan + "&tahun=" + tahun;
    });

    $('#preview').on('click', function () {
        let bulan = $('#bulan').val();
        let tahun = $('#tahun').val();

        $.ajax({
            url: BASE_URL + "Laporan/get_total_iuran",
            type: 'GET',
            data: { bulan: bulan, tahun: tahun },
            dataType: 'json',
            success: function (response) {
                let formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(response.total);
                $('.total-iuran').html(formatted);
            }
        });
    });
});

function tableLapIuran(bulan, tahun) {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapIuran",
        type: "POST",
        data: { bulan: bulan, tahun: tahun }, // Kirim parameter
        success: function (data) {
            $('#div-table-lap-iuran').html(data);
            $('#tableIuran').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

