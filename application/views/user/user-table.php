<table class="table table-bordered" id="tableUser" width="100%" cellspacing="0">
    <thead>
        <tr>
            <!-- <th>No</th> -->
            <th>No.</th>
            <th>Nama User</th>
            <th>Username</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $x=1;
        foreach ($user as $row) { ?>
            <tr>
                    <td><?= $x++; ?></td>
                    <td><?= $row->nama_user ?></td>
                    <td><?= $row->username ?></td>
                    <td><?= $row->level_name ?></td>
                    <td>
                        <button class="btn btn-warning edit" data-id="<?= $row->id ?>"><i class="fa fa-edit"></i></button>
                    </td>
                </tr>
        <?php }
        ?>
    </tbody>
</table>