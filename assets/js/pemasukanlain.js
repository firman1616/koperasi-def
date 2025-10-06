$(document).ready(function () {
    tablePemasukanLain();
    $('#id').val('');
    $('#pemasukanForm').trigger("reset");
    
    
    $('#save-data').click(function (e) { 
        e.preventDefault();
        let id = $('#id').val(); // Cek apakah #id memiliki nilai
        let message = id ? "Data Berhasil Diupdate!" : "Data Berhasil Ditambahkan!";
        let kategori = $('#kategori').val(); // Ambil nilai kategori dan hilangkan spasi kosong
        let nominal = $('#nominal').val(); 
        let keterangan = $('#keterangan').val(); 

        if (!kategori || !nominal || !keterangan) {
            alert("Kategori, Nominal dan Keterangan harus diisi!");
            return;
        }

        $.ajax({
            data: $('#pemasukanForm').serialize(),
            url: BASE_URL + "PemasukanLain/store",
            type: "POST",
            datatype: 'json',
            success: function(data) {
                $('#pemasukanForm').trigger("reset");
                $('#id').val(''); // Reset ID setelah submit
                alert(message);
                tablePemasukanLain();
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
            url: BASE_URL + "PemasukanLain/vedit/" + id,
            type: 'GET',
            dataType : 'json',            
            success: function (data) {
                console.log(data);
                $('#id').val(id);
                $('#kategori').val(data.kategori_id).trigger('change');
                $('#nominal').val(data.nominal);
                $('#keterangan').val(data.keterangan);
            }
        })
    })

});

function tablePemasukanLain() {
    $.ajax({
        url: BASE_URL + "PemasukanLain/tablePemasukanLain",
        type: "POST",
        success: function (data) {
            $('#div-table-pemasukan-lain').html(data);
            $('#tablePemasukanLain').DataTable({
                "processing": true,
                "responsive": true,
            });
        }
    });
}

$(document).on('click', '.delete-btn', function (event) {
    event.preventDefault();

    var url = $(this).attr('href'); // Ambil URL dari href

    if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(response) {
                alert(response.message);
                tablePemasukanLain(); // Refresh table setelah hapus
            },
            error: function(xhr) {
                alert("Terjadi kesalahan saat menghapus data!");
            }
        });
    }
});


document.addEventListener("DOMContentLoaded", function () {
    let inputNominal = document.getElementById("nominal");
    let inputHidden = document.getElementById("nominal_raw");

    inputNominal.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Hanya angka
        let formattedValue = formatRupiah(value);
        e.target.value = formattedValue;
        inputHidden.value = value; // Simpan nilai asli tanpa format
    });

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
