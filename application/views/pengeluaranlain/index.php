<!-- Begin Page Content -->
<style>
    .saldo-text {
        color: red;
        /* Warna merah */
        font-weight: bold;
        /* Membuat teks lebih tebal */
        font-size: 17px;
        /* Ukuran teks sedikit lebih besar */
    }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                </div>
                <div class="card-body">
                    <form action="" id="pengeluaran" name="pengeluaran" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="namaTransaksi">Tanggal Pengeluaran</label>
                            <input type="date" name="tgl_pengeluaran" id="tgl_pengeluaran" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="namaTransaksi">Kategori</label>
                            <select name="kategori" id="kategori" class="form-control" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php foreach ($keluar->result() as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="namaTransaksi">Sumber Dana</label>
                            <select name="sumberdana" id="sumberdana" class="form-control" required>
                                <option value="" disabled selected>Pilih Sumber Dana</option>
                                <?php foreach ($sumber->result() as $row) { ?>
                                    <option value="<?= $row->kategori_keuangan ?>"><?= $row->name ?></option>
                                <?php }
                                ?>
                            </select>
                            <small id="saldoTersedia" class="saldo-text">Rp. 0,-</small>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="text" class="form-control" id="nominal" name="nominal" required>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="save-data" style="margin-top: 10px;"><i class="fa fa-save"></i> | Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="div-table-pengeluaran-lain"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>