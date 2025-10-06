<?php 
foreach ($sum_nominal as $row) { 
    $in = $row->pemasukan;
    $out = $row->pengeluaran;
 }
?>
<!-- <table>
    <tr>
        <td>Total Pemasukan</td>
        <td>:</td>
        <td>Rp. <?= number_format(isset($in) ? $in : 0) ?></td>
    </tr>
    <tr>
        <td>Total Pengeluaran</td>
        <td>:</td>
        <td>Rp. <?= number_format(isset($out) ? $out : 0) ?></td>
    </tr>
</table> -->

<?php
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
$formatter->setPattern('MMMM yyyy');
?>

<table class="table table-bordered" id="tableKeuangan" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <!-- <th>Periode</th> -->
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $x = 1;
        $total_pemasukan = 0;
        $total_pengeluaran = 0;

        foreach ($keuangan as $row) {
            if ($row->kode == '1') { // Kategori 1 dianggap sebagai pemasukan
                $total_pemasukan += $row->nominal;
            } elseif ($row->kode == '2') { // Kategori 2 dianggap sebagai pengeluaran
                $total_pengeluaran += $row->nominal;
            }
        ?>
            <tr>
                <td><?= $x++; ?></td>
                <td><?= $row->kateg_trans ?></td>
                <!-- <td><?= $formatter->format(new DateTime($row->periode)) ?></td> -->
                <td>Rp. <?= number_format($row->nominal) ?></td>
            </tr>
        <?php } ?>

        <!-- Baris tambahan untuk Total Pemasukan, Total Pengeluaran, dan Saldo Akhir -->
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Total Pemasukan</strong></td>
            <td colspan="2"><strong>Rp. <?= number_format($in) ?></strong></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Total Pengeluaran</strong></td>
            <td colspan="2"><strong>Rp. <?= number_format($out) ?></strong></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Saldo Akhir</strong></td>
            <td colspan="2"><strong>Rp. <?= number_format($in - $out) ?></strong></td>
        </tr>
    </tbody>
</table>
