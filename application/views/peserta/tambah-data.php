<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Peserta/tambah_data') ?>" method="post">
                <label class="form-label">No Anggota</label>
                <input type="text" class="form-control" id="no_anggota" name="no_anggota" required>

                <label class="form-label">Nama Anggota</label>
                <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" required>

                <label class="form-label">NIK</label>
                <input type="number" class="form-control" id="nik" name="nik" required>

                <label class="form-label">No Telepon</label>
                <input type="number" class="form-control" id="phone" name="phone" required>

                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="" disabled selected>Pilih Salah Satu</option>
                            <option value="laki">Laki - Laki</option>
                            <option value="peremouan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Bin / Binti</label>
                        <input type="text" class="form-control" id="binbinti" name="binbinti" required>
                    </div>
                </div>


                <div class="row">
                    <div class="col">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Kongsi 1</label>
                        <input type="text" class="form-control" id="kongsi1" name="kongsi1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Kongsi 2</label>
                        <input type="text" class="form-control" id="kongsi2" name="kongsi2" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Kongsi 3</label>
                        <input type="text" class="form-control" id="kongsi3" name="kongsi3" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;" ><i class="fa fa-save"></i> | Simpan Data</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->