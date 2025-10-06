<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                </div>
                <div class="card-body">
                    <form action="" id="pemasukanForm" name="pemasukanForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="namaTransaksi">Kategori</label>
                            <select name="kategori" id="kategori" class="form-control" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php foreach ($kateg->result() as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                <?php }
                                ?>
                            </select>
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

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="div-table-pemasukan-lain"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>