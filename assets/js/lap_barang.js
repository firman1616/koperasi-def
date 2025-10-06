$(document).ready(function () {
    $("#detailLaporan").hide();

    // Event handler untuk tombol "Lihat"
    $("#preview").on("click", function () {
        $("#detailLaporan").slideToggle(); // Tampilkan/sembunyikan dengan efek slide
    });

    tableLapBarang();

    // Fungsi untuk mendapatkan tanggal dalam format YYYY-MM-DD
    function getFormattedDate(offsetDays = 0) {
        let date = new Date();
        date.setDate(date.getDate() + offsetDays);
        return date.toISOString().split('T')[0]; // Format YYYY-MM-DD
    }

    // Set default value ke input tanggal (30 hari lalu - hari ini)
    $("#date_start").val(getFormattedDate(-30)); // 30 hari yang lalu
    $("#date_end").val(getFormattedDate(0)); // Hari ini

    // Event klik tombol detail
    $(document).on("click", ".btn-detail", function () {
        var barang_id = $(this).data("id");
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();

        // Pastikan input tanggal tidak kosong
        date_start = date_start ? date_start : getFormattedDate(-30);
        date_end = date_end ? date_end : getFormattedDate(0);

        $("#detailTableBody").empty();

        $.ajax({
            url: BASE_URL + "Laporan/getHistoryBarang",
            type: "POST",
            data: {
                id: barang_id,
                date_start: date_start,
                date_end: date_end
            },
            dataType: "json",
            success: function (response) {
                $("#detailModalLabel").text("Detail History Barang - " + response.nama_barang);

                if (response.history.length > 0) {
                    response.history.forEach(function (item) {
                        let formattedDate = formatDate(item.history_date);
                        $("#detailTableBody").append(`
                            <tr>
                                <td>${item.qty}</td>
                                <td>${formattedDate}</td>
                            </tr>
                        `);
                    });
                } else {
                    $("#detailTableBody").append('<tr><td colspan="2" class="text-center">No data available</td></tr>');
                }
                $("#detailModal").modal("show");
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

    $("#export_excel").click(function () {
        window.location.href = BASE_URL + "Laporan/export_barang";
    });

    // Fungsi perbaikan untuk format tanggal ke format DD-MM-YYYY
    function formatDate(dateString) {
        if (!dateString || dateString === "0000-00-00") return "-"; // Jika tanggal kosong atau invalid

        let [year, month, day] = dateString.split("-");
        return `${day}-${month}-${year}`; // Format DD-MM-YYYY
    }

});

function tableLapBarang() {
    $.ajax({
        url: BASE_URL + "Laporan/tableLapBarang",
        type: "POST",
        success: function (data) {
            $('#div-table-lap-barang').html(data);
            $('#tableLapBarang').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
