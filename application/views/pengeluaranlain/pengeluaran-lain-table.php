<table class="table table-bordered" id="tablePengeluaranLain" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori Pengeluaran</th>
            <th>Sumber Dana</th>
            <th>Nominal</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
        <?php 
        $x=1;
        foreach ($pengeluaran as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->kategori ?></td>
                <td><?= $row->sumber_dana ?></td>
                <td>Rp. <?= number_format($row->nominal) ?></td>
                <td><?= date('Y-m-d', strtotime($row->date)) ?></td>
                <td><?= $row->keterangan ?></td>
                <!-- <td>
                    <button type="button" class="btn btn-warning edit" data-id="<?= $row->id ?>"><i class="fa fa-edit"></i></button>
                    <a href="<?= site_url('PengeluaranLain/delete_data/'.$row->id) ?>" data-id="<?= $row->id ?>" class="btn btn-danger delete-btn" data-id="<?= $row->id ?>"><i class="fa fa-trash"></i></a>
                </td> -->
            </tr>
        <?php }
        ?>
    </tbody>
</table>