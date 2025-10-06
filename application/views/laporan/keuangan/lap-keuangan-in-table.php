<table class="table table-bordered" id="tableKeuanganIn" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Nominal</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($keuangan_masuk as $row) { ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row->kategori_trans; ?></td>
                <td><?= number_format($row->nominal, 0, ",", "."); ?></td>
                <td><?= date('d-m-Y', strtotime($row->date)); ?></td>
                <td><?= $row->keterangan; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>