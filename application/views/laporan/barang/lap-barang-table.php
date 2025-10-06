<table class="table table-bordered" id="tableLapBarang" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>QTY</th>
            <th>Harga Beli Barang</th>
            <th>Harga Jual Barang</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($barang as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->kode_barang ?></td>
                <td><?= $row->nama_barang ?></td>
                <td><?= $row->qty ?></td>
                <td><?= 'Rp. ' .number_format($row->harga_beli) ?></td>
                <td><?= 'Rp. ' .number_format($row->harga_jual) ?></td>
                <td>
                    <button type="button" class="btn btn-primary btn-detail" data-id="<?= $row->id ?>">
                        <i class="fa fa-list"></i>
                    </button>
                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail History Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>QTY</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        <!-- Data akan dimasukkan lewat AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
