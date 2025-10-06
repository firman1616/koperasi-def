$(document).ready(function() {
    tableUOM();
    $('#id').val('');
    $('#uomForm').trigger("reset");
    
    $('#save-data').click(function (e) { 
        e.preventDefault();

        // Swal.fire({
        //     icon: 'info',
        //     title: 'Data Sedang diproses',
        //     showConfirmButton: false,
        //     // timer: 3000
        // })

        $.ajax({
            data: $('#uomForm').serialize(),
            url: BASE_URL + "UOM/store",
            type: "POST",
            datatype: 'json',
            success: function(data) {
                $('#uomForm').trigger("reset");
                // Swal.fire({
                //     icon: 'success',
                //     title: 'Success',
                //     text: 'Data Berhasil disimpan',
                //     showConfirmButton: false,
                //     timer: 1500
                // })
                tableUOM();
            },
            error: function(data) {
                console.log('Error:', data);
                $('$save-data').html('Simpan Data');
            }
        });
     })

     $('body').on('click','.edit',function (e) {
        var id = $(this).data('id');
        $.ajax({
            url: BASE_URL + "UOM/vedit/" + id,
            type: 'GET',
            dataType : 'json',            
            success: function (data) {
                console.log(data);
                $('#id').val(id);
                $('#kode_satuan').val(data.kode);
                $('#nama_satuan').val(data.uom);
                
            }
        })
    })


});

function tableUOM() {
    $.ajax({
        url: BASE_URL + "UOM/tableUOM",
        type: "POST",
        success: function (data) {
            $('#div-table-uom').html(data);
            $('#tableUOM').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
