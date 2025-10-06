<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <div class="row">
        <!-- <div class="col"> -->
            <div class="card shadow mb-4 col-lg-6">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
                </div>
                <div class="card-body">
                    <!-- <label class="label">Tanggal Transaksi</label> -->
                    <form method="GET" action="<?= base_url('Laporan/export_excel_keuangan') ?>" id="laporanForm">
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col">
                                <input type="date" class="form-control" name="date_start" id="date_start">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="date_end" id="date_end">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col">
                                <select name="kategori_utama" id="kategori_utama" class="form-control">
                                    <option value="">Pilih kategori Transaksi</option>
                                    <option value="1">Pemasukan</option>
                                    <option value="2">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="col">
                                <!-- <select name="kategori" id="kategori" class="form-control">
                                    <option value="">Pilih kategori Keuangan</option>
                                    <?php
                                    foreach ($kategori->result() as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->name ?></option>
                                    <?php }
                                    ?>
                                </select> -->
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="preview">Lihat</button>
                        <button type="submit" class="btn btn-success" id="export_excel">Export</button>
                        <button type="submit" class="btn btn-danger" id="export_excel_all">Export All</button>
                    </form>
                </div>
            </div>
        <!-- </div> -->
        <!-- <div class="col">
            <div>
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="kategori-text">
                                    Total
                                </div>
                                <div class="h1 mb-0 font-weight-bold text-gray-800" id="total-transaksi">
                                    0
                                </div>

                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-check-alt fa-6x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>


    <div class="card shadow mb-4" id="detail_keuangan">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Keuangan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="div-table-lap-keuangan"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4" id="detail_in">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Kategori Pemasukan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="div-table-lap-pemasukan-keuangan"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4" id="detail_out">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Kategori Pengeluaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="div-table-lap-pengeluaran-keuangan"></div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->