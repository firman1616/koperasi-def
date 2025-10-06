$(document).ready(function() {
    tableBarangHabis();
    tableBarangHampirHabis();
});

function tableBarangHabis() {
    $.ajax({
        url: BASE_URL + "Dashboard/tableBarangHabis",
        type: "POST",
        success: function (data) {
            $('#div-table-barang-habis').html(data);
            $('#tableBarangHabis').DataTable({
                "processing": true,
                "responsive": true,
                "ordering": true,
                "paging": false,
                "scrollY": "300px", // Scroll vertikal dengan tinggi tetap 400px
                "scrollCollapse": true,
            });
        }
    });
}

function tableBarangHampirHabis() {
    $.ajax({
        url: BASE_URL + "Dashboard/tableBarangHampirHabis",
        type: "POST",
        success: function (data) {
            $('#div-table-barang-hampir-habis').html(data);
            $('#tableBarangHampirHabis').DataTable({
                "processing": true,
                "responsive": true,
                "ordering": true,
                "paging": false,
                "scrollY": "300px", // Scroll vertikal dengan tinggi tetap 400px
                "scrollCollapse": true,
            });
        }
    });
}
