<table class="table table-bordered" id="tableIuran" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Anggota</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($lap_iuran as $row) { ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->nama_anggota ?></td>
                <td>
                    <?php 
                    if ($row->status != '1') {
                        echo '<span class="badge badge-danger">Belum Bayar</span>';
                    }else {
                        echo '<span class="badge badge-primary">Lunas</span>';
                    }
                    ?>
                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>