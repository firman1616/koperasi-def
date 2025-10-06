<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <a href="<?= site_url('Barang/vtambah') ?>" class="btn btn-primary" style="margin-bottom: 10px;" ><i class="fa fa-plus"></i> | Tambah Data</a>
                <a href="<?= site_url('Barang/export_data') ?>" class="btn btn-success" style="margin-bottom: 10px;" ><i class="fa fa-download"></i> | Export Data</a>
                <div id="div-table-barang"></div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<div class="modal fade" id="modalUpdateStok" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Update Stok Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUpdateStok">
                    <input type="hidden" id="id_barang" name="id_barang">
                    <div class="mb-3">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Qty</label>
                        <input type="number" class="form-control" id="qty" name="qty" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update Stok</button>
                </form>
            </div>
        </div>
    </div>
</div>