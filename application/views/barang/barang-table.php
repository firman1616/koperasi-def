<table class="table table-bordered" id="tableBarang" width="100%" cellspacing="0">
    <thead>
        <tr>
            <!-- <th>No</th> -->
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Margin</th>
            <th>Qty</th>
            <th>UoM</th>
            <th>Tgl Update Stock</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($barang as $row) { ?>
        <tr>
            <td><?= $row->kode_barang ?></td>
            <td><?= $row->nama_barang ?></td>
            <td><?= number_format($row->harga_jual,2) ?></td>
            <td><?= number_format($row->margin) ?></td>
            <td><?= $row->qty ?></td>
            <td><?= $row->uom ?></td>
            <td><?= date('d-m-y h:i:s', strtotime($row->tgl_update_stock)) ?></td>
            <td>
                <button type="button" class="btn btn-success btn-update-stok" data-id="<?= $row->id; ?>" title="update stock"><i class="fa fa-sync-alt"></i></button>
                <a href="<?= site_url('Barang/vedit/'.$row->id) ?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                <a href="<?= site_url('Barang/update_status/'.$row->id) ?>" class="btn btn-danger" title="update status barang" onclick="return confirm('Apakah Anda yakin ingin Menonaktifkan barang ini?');"><i class="fa fa-power-off"></i></a>
            </td>
        </tr>
        <?php }
        ?>
    </tbody>
</table>