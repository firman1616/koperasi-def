<table class="table table-striped" id="tableBarangHabis">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">QTY</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        foreach ($qty_0 as $row) { ?>
            <tr>
                <th scope="row"><?= $x++ ?></th>
                <td><?= $row->kode_barang ?></td>
                <td><?= $row->nama_barang ?></td>
                <td><?= $row->qty ?></td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>