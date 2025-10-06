<table class="table table-bordered" id="tableKeuanganOut" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Sumber Dana</th>
            <th>Nominal</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $x=1;
        foreach ($keuangan_keluar as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->nama_kategori ?></td>
                <td><?= $row->nama_sumber_dana ?></td>
                <td><?= number_format($row->nominal, 0, ",", "."); ?></td>
                <td><?= date('d-m-Y', strtotime($row->date)); ?></td>
                <td><?= $row->keterangan ?></td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>