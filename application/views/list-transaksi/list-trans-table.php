<table class="table table-bordered" id="tableListTrans" width="100%" cellspacing="0">
    <thead>
        <tr>

            <th>No</th>
            <th>No. Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Total Transaksi</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($list as $row) { ?>
            <tr>
                <td><?= $x++ ?></td>
                <td><?= $row->no_transaksi ?></td>
                <td>
                    <?php
                    if ($row->pelanggan_id == '117') {
                        echo $row->lainnya;
                    } else {
                        echo $row->name;
                    }
                    ?>
                </td>
                <td>Rp. <?= number_format($row->grand_total) ?></td>
                <td>
                    <!-- <a href="<?= site_url('Transaksi/cetak_struk/' . $row->id) ?>" class="btn btn-primary" title="cetak struk"><i class="fa fa-print"></i></a> -->
                    <a href="javascript:void(0);" onclick="printStruk('<?= site_url('Transaksi/cetak_struk/' . $row->id) ?>')" class="btn btn-primary" title="Cetak Struk">
                        <i class="fa fa-print"></i>
                    </a>
                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>