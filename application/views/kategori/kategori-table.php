<table class="table table-bordered" id="tableKateg" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Sub Kategori</th>
            <th>Kode Kategori</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($sub as $row) { ?>
            <tr>
                <td><?= $x++ ?></td>
                <td><?= $row->name ?></td>
                <td><?= $row->kode ?></td>
                <!-- <td><button type="button" class="btn btn-warning edit" data-id="<?= $row->id ?>"><i class="fa fa-edit"></i></button></td> -->
            </tr>
        <?php }
        ?>
    </tbody>
</table>