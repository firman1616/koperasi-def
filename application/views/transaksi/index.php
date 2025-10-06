<!-- Begin Page Content -->
<style>
    /* .container-box {
        padding: 20px;
        border-radius: 10px;
        background-color: white;
        margin-bottom: 20px;
    } */

    .nota {
        float: right;
        font-weight: bold;
        color: gray;
        /* font-size: 50px; */
    }

    .total-harga {
        float: right;
        font-size: 80px;
        font-weight: bold;
        color: red;
        clear: both;
    }

    .button-group {
        display: flex;
        gap: 20px;
    }

    .form-control-sm {
        width: 250px;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        font-weight: bold;
    }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= $subtitle ?></h6>
        </div>
        <div class="card-body">


            <!-- <div class="container mt-12"> -->
            <!-- <div class="container-box mt-3"> -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Barcode</strong></label>
                        <select id="barcode" class="form-control transelect2" onchange="return autofill();" style="width: 100%">
                            <option value=""></option>
                            <!-- Data barang akan dimuat melalui AJAX -->
                        </select>
                        <br>
                        <small>Stock : <b id="qty-tersedia">-</b> </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Jumlah</strong></label>
                        <input type="number" id="jumlah" class="form-control" placeholder="Jumlah">
                    </div>
                    <div class="button-group">
                        <button id="tambahBtn" class="btn btn-success" disabled>Tambah</button>
                        <button id="bayarBtn" class="btn btn-success" disabled>Bayar</button>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="nota">Nota: <?= $kd_trans ?> <button type="button" class="btn btn-default" id="refreshKode"><i class="fa fa-sync"></i></button></div> 
                    <input type="hidden" name="kd_trans" id="kd_trans" value="<?= $kd_trans ?>">
                    <input type="hidden" name="id_akhir" id="id_akhir" value="<?= $id_akhir ?>">
                    <input type="hidden" name="id_user" id="id_user" value="<?= $id_user ?>">
                    <div class="total-harga">Rp. 0,-</div>
                </div>
            </div>
            <!-- </div> -->
            <div class="table-responsive" style="margin-top: 15px;">
                <div id="div-table-transaksi"></div>
            </div>
            <!-- </div> -->


        </div>
    </div>
</div>
<!-- /.container-fluid -->


<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" role="dialog" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPembayaranLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPembayaran">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    </div> -->
                    <div class="form-group">
                        <label for="anggota">Pilih Anggota</label>
                        <select id="anggota" name="anggota" class="form-control" style="width: 100%;">
                            <option value="" disabled selected>Pilih Anggota</option>
                        </select>
                    </div>
                    <div id="formTambahan" style="display: none;">
                        <label for="extraField">Pelanggan Lainnya</label>
                        <input type="text" id="extraField" name="extraField" class="form-control" placeholder="Pelanggan Lainnya">
                    </div>
                    <label for="metode_bayar">Metode Pembayaran</label>
                    <select id="metode_bayar" name="metode_bayar" class="form-control">
                        <option value="1">Cash</option>
                        <option value="2">Tempo</option>
                    </select>

                    <label for="uang_dibayarkan">Uang yang Dibayarkan</label>
                    <input type="number" id="uang_dibayarkan" name="uang_dibayarkan" class="form-control" required>
                    <!-- <div class="form-group">
                        <label for="uang_dibayarkan">Uang yang Dibayarkan</label>
                        <input type="number" class="form-control" id="uang_dibayarkan" name="uang_dibayarkan" required>
                    </div> -->
                    <!-- <div class="form-group">
                        <label for="diskon">Diskon (%)</label>
                        <input type="number" class="form-control" id="diskon" name="diskon" value="0">
                    </div> -->
                    <p>Total Bayar: <span id="total_bayar" data-total="">Rp. 0</span></p>
                    <p>Total Pengembalian: <span id="total_kembalian">Rp. 0</span></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="prosesBayar">Bayar</button>
                <button type="button" class="btn btn-warning" id="bayarCetakBtn">Bayar & Cetak</button>
            </div>
        </div>
    </div>
</div>