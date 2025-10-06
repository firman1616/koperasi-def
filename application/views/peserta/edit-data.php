<?php foreach ($edit->result() as $row) {
    $nama = $row->name;
    $a = $row->no_agt;
    $b = $row->jk;
    $c = $row->binbinti;
    $d = $row->tmp_lahir;
    $e = $row->nik;
    $f = $row->alamat;
    $g = $row->kongsi1;
    $h = $row->kongsi2;
    $i = $row->kongsi3;
    $j = $row->no_telp;
    $k = $row->tgl_lahir;
    $id = $row->id;
} ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?> <?= $nama ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Peserta/update_data/' . $id) ?>" method="post">
                <label class="form-label">No Anggota</label>
                <input type="text" class="form-control" id="no_anggota" name="no_anggota" value="<?= $a ?>">

                <label class="form-label">Nama Anggota</label>
                <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" value="<?= $nama ?>">

                <label class="form-label">NIK</label>
                <input type="number" class="form-control" id="nik" name="nik" value="<?= $e ?>">

                <label class="form-label">No Telepon</label>
                <input type="number" class="form-control" id="phone" name="phone" value="<?= $j ?>">

                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $f ?>">

                <div class="row">
                    <div class="col">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="" disabled selected>Pilih Salah Satu</option>
                            <option value="laki" <?= ($b == 'laki') ? 'selected' : '' ?>>Laki - Laki</option>
                            <option value="perempuan" <?= ($b == 'perempuan') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Bin / Binti</label>
                        <input type="text" class="form-control" id="binbinti" name="binbinti" value="<?= $c ?>">
                    </div>
                </div>


                <div class="row">
                    <div class="col">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= $d ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= $k ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Kongsi 1</label>
                        <input type="text" class="form-control" id="kongsi1" name="kongsi1" value="<?= $g ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Kongsi 2</label>
                        <input type="text" class="form-control" id="kongsi2" name="kongsi2" value="<?= $h ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Kongsi 3</label>
                        <input type="text" class="form-control" id="kongsi3" name="kongsi3" value="<?= $i ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;"><i class="fa fa-upload"></i> | Update Data</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->