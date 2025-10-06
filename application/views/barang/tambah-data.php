<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('Barang/tambah_data') ?>" method="post">
                <label class="form-label">Kode Barang</label>
                <input type="text" class="form-control" id="kode_barang" name="kode_barang" readonly value="<?= $kd_barang ?>">

                <label class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="0">
                    </div>
                    <div class="col">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="0">
                    </div>
                    <div class="col">
                        <label class="form-label">QTY</label>
                        <input type="number" class="form-control" id="qty" name="qty" value="0">
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col">
                        <label class="form-label">Satuan</label>
                        <select name="uom" id="uom" class="form-control select2" required>
                            <option value="" disabled selected>Pilih satuan</option>
                            <?php foreach ($uom as $row) { ?>
                                <option value="<?= $row->kode ?>"><?= $row->kode ?> - <?= $row->uom ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Jenis Barang</label>
                        <select name="jenis_barang" id="jenis_barang" class="form-control" required>
                            <option value="" disabled selected>Pilih Satu</option>
                            <option value="1">Raw Material</option>
                            <option value="2">Siap Jual</option>
                        </select>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col">
                        <label class="form-label">Set Diskon <small>3000</small></label>
                        <input type="number" class="form-control" id="set_diskon" name="set_diskon" value="0">
                    </div>
                    <div class="col">
                        <label class="form-label">Min Qty </label>
                        <input type="number" class="form-control" id="min_qty" name="min_qty" value="0">
                    </div>
                </div> -->
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;"><i class="fa fa-save"></i> | Simpan Data</button>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->