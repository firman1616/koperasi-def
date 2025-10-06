$(document).ready(function() {
    tableListTrans();
    // tableDetailLot();
    $('#id').val('');
    $('#modulForm').trigger("reset");
});

function printStruk(url) {
    var printWindow = window.open(url, "_blank");

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
}

function tableListTrans() {
    $.ajax({
        url: BASE_URL + "Transaksi/tableListTrans",
        type: "POST",
        success: function (data) {
            $('#div-table-list-trans').html(data);
            $('#tableListTrans').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
