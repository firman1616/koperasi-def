<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                </div>
                <div class="card-body">
                    <form action="" id="kategForm" name="kategForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <label class="form-label">Nama Sub kategori</label>
                        <input type="text" class="form-control" id="nama_sub" name="nama_sub" required>
                        <label class="form-label">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <?php foreach ($kateg->result() as $row) { ?>
                                <option value="<?= $row->id ?>"><?= $row->kode ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-primary" id="save-data" style="margin-top: 10px;"><i class="fa fa-save"></i> | Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="div-table-kateg"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>


</div>
<!-- /.container-fluid -->