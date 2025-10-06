<table class="table table-bordered" id="tableTempo" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Nominal</th>
            <th>Tgl Transaksi Tempo</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($tempo as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
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
                <td><?= 'Rp. ' . number_format($row->grand_total) ?></td>
                <td><?= date('d-m-Y', strtotime($row->tgl_transaksi)) ?></td>
                <td>
                    <!-- <button type="button" class="btn btn-primary" id="bayarUtang"><i class="fa fa-money-bill-wave"></i></button> -->
                    <button class="btn btn-info btn-sm openModalTempo" data-transaksi="<?= $row->no_transaksi ?>" data-nominal="<?= $row->grand_total ?>" data-id="<?= $row->id ?>">
                        <i class="fa fa-money-bill-wave"></i>
                    </button>
                </td>
            </tr>
        <?php  }
        ?>
    </tbody>
</table>