<table class="table table-bordered" id="tableUOM" width="100%" cellspacing="0">
    <thead>
        <tr>
            <!-- <th>No</th> -->
            <th>No.</th>
            <th>Kode SAtuan</th>
            <th>Nama Satuan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $x=1;
        foreach ($uom as $row) { ?>
        <tr>
            <td><?= $x++; ?></td>
            <td><?= $row->kode ?></td>
            <td><?= $row->uom ?></td>
            <td>
                <button type="button" class="btn btn-warning edit" data-id="<?= $row->id ?>"><i class="fa fa-edit"></i></button>
            </td>
        </tr>
        <?php }
        ?>
    </tbody>
</table>