<?php foreach ($edit->result() as $row) {
    $a = $row->kode_barang;
    $b = $row->nama_barang;
    $c = $row->harga_beli;
    $d = $row->harga_jual;
    $e = $row->qty;
    $f = $row->uom;
    $g = $row->margin;
    $id  = $row->id;
    $h = $row->set_diskon;
    $i = $row->min_qty;
    $j = $row->status_barang;
} ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?> <?= $b ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Barang/update_data/' . $id) ?>" method="post">
            <label class="form-label">Kode Barang</label>
                <input type="text" class="form-control" id="kode_barang" name="kode_barang" value="<?= $a; ?>" readonly>

                <label class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= $b ?>">

                <div class="row">
                    <div class="col">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="<?= $c ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="<?= $d ?>">
                    </div>
                    <!-- <div class="col">
                        <label class="form-label">Margin</label>
                        <input type="number" class="form-control" id="margin" name="margin" value="<?= $g ?>">
                    </div> -->
                    <div class="col">
                        <label class="form-label">UoM</label>
                        <select name="uom" id="uom" class="form-control" required>
                            <option value="" disabled selected>Pilih satuan</option>
                            <?php foreach ($uom as $rop) { ?>
                                <option value="<?= $rop->kode ?>" <?php if ($f == $rop->kode) {
                                    echo "selected";
                                } ?> ><?= $rop->uom ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Jenis Barang</label>
                        <select name="jenis_barang" id="jenis_barang" class="form-control" required>
                            <option value="" disabled selected>Pilih Satu</option>
                            <option value="1" <?= ($j == '1') ? 'selected' : '' ?>>Raw Material</option>
                            <option value="2" <?= ($j == '2') ? 'selected' : '' ?>>Siap Jual</option>
                            
                            
                        </select>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col">
                        <label class="form-label">Set Diskon <small>3000</small></label>
                        <input type="number" class="form-control" id="set_diskon" name="set_diskon" value="<?= $h ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Min Qty </label>
                        <input type="number" class="form-control" id="min_qty" name="min_qty" value="<?= $i ?>">
                    </div>
                </div> -->

                <!-- <div class="row"> -->
                    <!-- <div class="col">
                        <label class="form-label">QTY</label>
                        <input type="text" class="form-control" id="qty" name="qty" value="<?= $e ?>">
                    </div> -->
                    
                <!-- </div> -->
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;"><i class="fa fa-upload"></i> | Update Data</button>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->