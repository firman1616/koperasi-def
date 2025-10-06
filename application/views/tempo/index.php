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
                <!-- <a href="<?= site_url('Barang/vtambah') ?>" class="btn btn-primary" style="margin-bottom: 10px;" ><i class="fa fa-plus"></i> | Tambah Data</a> -->
                <div id="div-table-tempo"></div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Pembayaran -->
<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="modalDetailTempo" tabindex="-1" aria-labelledby="modalDetailTempoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailTempoLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_transaksi" id="id_transaksi">
                <div class="form-group">
                    <label for="noTransaksi">No Transaksi:</label>
                    <input type="text" class="form-control" id="noTransaksi" readonly>
                </div>
                <div class="form-group">
                    <label for="nominalTransaksi">Nominal Tagihan:</label>
                    <input type="text" class="form-control" id="nominalTransaksi" readonly>
                </div>
                <div class="form-group">
                    <label for="nominalTransaksi">Nominal Pembayaran:</label>
                    <input type="text" class="form-control" id="nominalBayar" name="uang_bayar">
                </div>
                <div class="form-group">
                    <label for="nominalTransaksi">Nominal Kembalian:</label>
                    <input type="text" class="form-control" id="nominalKembali" value="0" readonly name="uang_kembali">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnBayar">Bayar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
