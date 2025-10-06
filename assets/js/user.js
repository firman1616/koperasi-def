$(document).ready(function() {
    tableUser();
    $('#id').val('');
    $('#userForm').trigger("reset");
    
    $('#save-data').click(function (e) { 
        e.preventDefault();

        // Swal.fire({
        //     icon: 'info',
        //     title: 'Data Sedang diproses',
        //     showConfirmButton: false,
        //     // timer: 3000
        // })

        $.ajax({
            data: $('#userForm').serialize(),
            url: BASE_URL + "User/store",
            type: "POST",
            datatype: 'json',
            success: function(data) {
                $('#userForm').trigger("reset");
                alert("User Berhasil Di tambahkan!");
                tableUser();
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
            url: BASE_URL + "User/vedit/" + id,
            type: 'GET',
            dataType : 'json',            
            success: function (data) {
                console.log(data);
                $('#id').val(id);
                $('#nama_user').val(data.nama_user);
                $('#username').val(data.username);
                $('#password').val(data.password);
                $('#level').val(data.level);
            }
        })
    })


});

function tableUser() {
    $.ajax({
        url: BASE_URL + "User/tableUser",
        type: "POST",
        success: function (data) {
            $('#div-table-user').html(data);
            $('#tableUser').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}
