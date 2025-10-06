<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <div class="card shadow mb-4 col-lg-5">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">
            <!-- <label class="label">Tanggal Transaksi</label> -->
            <label class="label">Tanggal Transaksi</label>
            <form method="GET" action="<?= base_url('Laporan/export_barang') ?>">
                <div class="row" style="margin-bottom: 5px;">
                    <div class="col">
                        <input type="date" class="form-control" name="date_start" id="date_start">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" name="date_end" id="date_end">
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="preview">Lihat</button>
                <button type="submit" class="btn btn-success" id="export_excel">Export</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4" id="detailLaporan" style="display: none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Laporan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="div-table-lap-barang"></div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->