$(document).ready(function () {
    tableBarang();
    $('.select2').select2({
        placeholder: "Pilih satuan",
        allowClear: true,
        width: '100%',
    });

    // console.log("Script barang.js berhasil dimuat");

    $(document).on("click", ".btn-update-stok", function () {
        let id_barang = $(this).data("id");
        console.log("Tombol Update Stok diklik, ID Barang:", id_barang);

        if (!id_barang) {
            alert("ID barang tidak ditemukan!");
            return;
        }

        $.ajax({
            url: BASE_URL + "Barang/get_barang_by_id",
            type: "POST",
            data: { id: id_barang },
            dataType: "json",
            success: function (response) {
                console.log("Response dari server:", response);
                if (response) {
                    $("#id_barang").val(response.id);
                    $("#kode_barang").val(response.kode_barang);
                    $("#nama_barang").val(response.nama_barang);
                    $("#modalUpdateStok").modal("show");
                } else {
                    alert("Data barang tidak ditemukan.");
                }
            },
            error: function (xhr, status, error) {
                console.log("Error AJAX get_barang_by_id:", error);
            }
        });
    });

    $("#formUpdateStok").submit(function(e) {
        e.preventDefault();
        
        let formData = {
            id: $("#id_barang").val(),
            qty: $("#qty").val(),
        };
    
        console.log("Mengirim data update:", formData); // Debugging
    
        $.ajax({
            url: BASE_URL + "Barang/update_stok",
            type: "POST",
            data: formData,
            success: function(response) {
                console.log("Response update_stok:", response);
                alert(response);
    
                // Reset form qty
                $("#formUpdateStok").trigger("reset");
    
                // Tutup modal setelah update stok berhasil
                $("#modalUpdateStok").modal("hide");
    
                // Reload tabel barang
                tableBarang();
            },
            error: function(xhr, status, error) {
                console.log("Error AJAX update_stok:", error);
            }
        });
    });
    
    // Reset form qty saat modal ditutup
    $("#modalUpdateStok").on("hidden.bs.modal", function () {
        $("#formUpdateStok").trigger("reset");
    });
    

});

function tableBarang() {
    $.ajax({
        url: BASE_URL + "Barang/tableBarang",
        type: "POST",
        success: function (data) {
            $('#div-table-barang').html(data);
            $('#tableBarang').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
