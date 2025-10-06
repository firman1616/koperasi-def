<table class="table table-bordered" id="tablePeserta" width="100%" cellspacing="0">
    <thead>
        <tr>
            <!-- <th>No</th> -->
            <th>No. Anggota</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Telpon</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>TTL</th>
            <th>Nama Orangtua</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // $x=1;
        foreach ($anggota as $row) {
            $kelamin = $row->jk;
        ?>
            <tr>
                <!-- <td><?= $x++; ?></td> -->
                <td><?= $row->no_agt ?></td>
                <td><?= $row->name ?></td>
                <td>
                    <?php
                    if ($kelamin == 'laki') {
                        echo 'Laki-laki';
                    } else {
                        echo 'Perempuan';
                    }
                    ?>
                </td>
                <td><?= $row->no_telp ?></td>
                <td><?= $row->nik ?></td>
                <td><?= $row->alamat ?></td>
                <td><?= $row->tmp_lahir . ", " . date('d-m-Y', strtotime($row->tgl_lahir)) ?></td>
                <td><?= $row->binbinti ?></td>
                <td>

                    <a href="<?= site_url('Peserta/vedit/' . $row->id) ?>" class="btn btn-warning" title="edit data"><i class="fa fa-edit"></i></a>
                    <a href="<?= site_url('Peserta/delete_data/' . $row->id) ?>"
                        class="btn btn-danger delete-btn"
                        title="hapus"
                        data-id="<?= $row->id ?>">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>