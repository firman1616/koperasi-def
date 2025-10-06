<table class="table table-bordered" id="tableLapTrans" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Pembeli</th>
            <th>Nominal</th>
            <th>Tanggal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($lap_trans as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->no_transaksi ?></td>
                <td>
                    <?php
                    if ($row->pelanggan_id == '117') {
                        echo $row->lainnya;
                    } else {
                        echo $row->cust;
                    }
                    ?>
                </td>
                <td><?= 'Rp. ' . number_format($row->grand_total) ?></td>
                <td><?= date('d-m-Y', strtotime($row->tgl_transaksi)) ?></td>
                <td>
                    <button type="button" class="btn btn-primary btn-detail"
                        data-id="<?= $row->id ?>">
                        <i class="fa fa-list"></i>
                    </button>
                    <!-- <a href="javascript:void(0);" onclick="printStruk('<?= site_url('Transaksi/cetak_struk/' . $row->id) ?>')" class="btn btn-primary" title="Cetak Struk">
                        <i class="fa fa-print"></i>
                    </a> -->
                    <a href="<?= site_url('Transaksi/cetak_struk/' . $row->id) ?>" class="btn btn-primary" title="cetak struk"><i class="fa fa-print"></i></a>
                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>


<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>QTY Beli Barang</th>
                            <th>Harga Beli Barang</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetailBody">
                        <!-- Data akan dimasukkan melalui AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>