<?php
foreach ($header as $row) {
  $no_trans = $row->no_transaksi;
  $tgl = $row->tgl_transaksi;
  $total = $row->grand_total;
  $cus_id = $row->pelanggan_id;
  $lain = $row->lainnya;
  $nama_cus = $row->name;
  $bayar = $row->uang_bayar;
  $kembali = $row->uang_kembali;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Belanja</title>
  <style>
    body {
      font-family: monospace;
      text-align: center;
      margin: 0;
      padding: 0;
      align-content: center;
      font-size: 10px
    }

    .receipt {
      width: 300px;
      margin: auto;
      white-space: pre-line;
    }

    .receipt .header,
    .receipt .footer {
      margin-bottom: 10px;
    }

    .receipt .items {
      text-align: left;
      margin-top: 10px;
    }

    .receipt .total {
      margin-top: 10px;
      font-weight: bold;
    }

    .receipt .divider {
      border-top: 1px dashed black;
      margin: 5px 0;
    }
  </style>
</head>

<body>
  <!--  <h3>KSU Asy-Syathibiyyah</h3>-->
  <img src="<?= base_url('assets/image/nota.png') ?>" width="163" height="56" style="align-content: center">
  <p style="margin-top: 0px; text-align: ">Jl. Kb. Dua Ratus, RT.4/RW.6 <br>
    Kec. Kalideres, Kota Jakarta Barat
  </p>

  <table width="24%" style="border-top: 1px dashed black; border-bottom: 1px dashed black; border-left: none; border-right: none; border-collapse: collapse;">
    <tbody>
      <tr>
        <td width="10%">No</td>
        <td width="5%">:</td>
        <td width="54%"><?= $no_trans ?></td> <br>
        <td width="54%"><?= date('d-m-Y', strtotime($tgl)) ?></td>
        <!--          <td width="22%">&nbsp;</td>-->
      </tr>
      <tr>
        <td width="10%">Pel</td>
        <td width="5%">:</td>
        <td colspan="2"><?= $nama_cus ?></td>
      </tr>
    </tbody>
  </table>

  <table width="24%">
    <tbody>
      <br>
      <?php
      foreach ($detail as $row) { ?>
        <tr>
          <td width="51%"><b><?= $row->nama_barang ?></b><br><?= number_format($row->total_harga, 0, ',', '.') ?> &nbsp;&nbsp;&nbsp;&nbsp; x<?= $row->qty ?> &nbsp;&nbsp;&nbsp; <?= number_format($row->qty * $row->total_harga, 0, ',', '.') ?></br></td>
          <!--
          <td width="6%"><?= $row->qty ?></td>
          <td width="21%"><?= $row->total_harga ?></td>
          <td width="22%"><?= number_format($row->qty * $row->total_harga, 0, ',', '.') ?></td>
-->
        </tr>
      <?php }
      ?>
    </tbody>
  </table>
  <br>

  <table width="24%" style="border-top: 1px dashed black; dashed black; border-left: none; border-right: none; border-collapse: collapse;">
    <tbody>
      <tr>
        <td width="49%"><span>Total Belanja</span></td>
        <td width="30%">&nbsp;</td>
        <td width="46%"><b><?= number_format($total, 0, ',', '.') ?></b></td>
        <!--      <td width="22%" style="border-top: 1px dashed black; border-bottom: 1px dashed black;">&nbsp;</td>-->
      </tr>
      <tr>
        <td>Tunai</td>
        <td align="center">=</td>
        <td style="border-bottom: 1px dashed black;"><b><?= number_format($bayar, 0, ',', '.') ?></b></td>
        <!--          <td>&nbsp;</td>-->
      </tr>
      <tr>
        <td><span>Kembalian</span></td>
        <td>&nbsp;</td>
        <td><i><?= number_format($kembali, 0, ',', '.') ?></i></td>
        <!--      <td style="border-bottom: 1px dashed black;">&nbsp;</td>-->
      </tr>
    </tbody>
  </table>
  <!-- Nama Pembeli : 
    <?php
    if ($cus_id == '117') {
      echo $lain;
    } else {
      echo $nama_cus;
    }
    ?> -->
  <br>Terimakasih sudah berbelanja di <br> KSU Asy-Syathibiyyah

</body>

</html>


<script>
  window.onload = function() {
    window.print();
  };
</script>