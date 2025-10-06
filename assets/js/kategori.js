$(document).ready(function () {
    tableKateg();
    $('#id').val('');
    $('#kategForm').trigger("reset");

    $('#save-data').click(function (e) {
        e.preventDefault();

        // Swal.fire({
        //     icon: 'info',
        //     title: 'Data Sedang diproses',
        //     showConfirmButton: false,
        //     // timer: 3000
        // })

        $.ajax({
            data: $('#kategForm').serialize(),
            url: BASE_URL + "Kategori/store",
            type: "POST",
            datatype: 'json',
            success: function (data) {
                $('#kategForm').trigger("reset");
                // Swal.fire({
                //     icon: 'success',
                //     title: 'Success',
                //     text: 'Data Berhasil disimpan',
                //     showConfirmButton: false,
                //     timer: 1500
                // })
                tableKateg();
            },
            error: function (data) {
                console.log('Error:', data);
                $('$save-data').html('Simpan Data');
            }
        });
    })

    $('body').on('click', '.edit', function (e) {
        var id = $(this).data('id');
        $.ajax({
            url: BASE_URL + "Kategori/vedit/" + id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#id').val(id);
                $('#nama_sub').val(data.name);
                $('#kategori').val(data.kategori_id).trigger('change');

            }
        })
    })


});

function tableKateg() {
    $.ajax({
        url: BASE_URL + "Kategori/tableKateg",
        type: "POST",
        success: function (data) {
            $('#div-table-kateg').html(data);
            $('#tableKateg').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
