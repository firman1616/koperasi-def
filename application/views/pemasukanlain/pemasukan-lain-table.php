<table class="table table-bordered" id="tablePemasukanLain" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori Pemasukan</th>
            <th>Nominal</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($pemasukan as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->kateg ?></td>
                <td>Rp. <?= number_format($row->nominal) ?></td>
                <td><?= date('d-m-Y', strtotime($row->date)) ?></td>
                <td><?= $row->keterangan ?></td>
                <!-- <td>
                    <button type="button" class="btn btn-warning edit" data-id="<?= $row->id ?>">
                        <i class="fa fa-edit"></i>
                    </button>
                    <a href="<?= site_url('PemasukanLain/delete_data/' . $row->id) ?>"
                        class="btn btn-danger delete-btn"
                        title="hapus"
                        data-id="<?= $row->id ?>">
                        <i class="fa fa-trash"></i>
                    </a>
                </td> -->
            </tr>
        <?php }
        ?>
    </tbody>
</table>


