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
      /* padding: 0; */
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
  <img src="<?= base_url('assets/image/nota.png') ?>" width="163" height="56">
  <p style="margin-top: 0px"><br> Jl. Kb. Dua Ratus, RT.4/RW.6 <br> Kec. Kalideres, Jakarta Barat
  </p>
  <center>
    <table width="85%" style="border-top: 1px dashed black; border-bottom: 1px dashed black; border-collapse: collapse;">
      <tbody>
        <tr>
          <td width="12%">No</td>
          <td width="1%" align="center">:</td>
          <td width="55%"><?= $no_trans ?></td>
          <td width="32%"><?= date('d-m-Y', strtotime($tgl)) ?></td>
          <!-- <td width="12%">Kasir</td>
          <td width="3%">:</td>
          <td width="28%">Nama Kasir</td> -->
        </tr>
        <tr>
          <td width="12%">Pel</td>
          <td width="1%" align="center">:</td>
          <td colspan="2">
            <?php
            if ($cus_id == '117') {
              echo $lain;
            } else {
              echo $nama_cus;
            }
            ?>
          </td>
          <!-- <td width="12%">Kasir</td>
          <td width="3%">:</td>
          <td width="28%">Nama Kasir</td> -->
        </tr>
      </tbody>
    </table>
	  
	  <table width="85%" style="border-bottom: 1px dashed black; border-left: none; border-right: none; border-collapse: collapse;">
      <tbody>
        <?php
        foreach ($detail as $row) { ?>
          <tr>
            <td width="51%"><b><?= $row->nama_barang ?></b></td>
            
          <td width="11%"><?= $row->qty ?></td>
          <td width="27%" style="text-align: right"><?= number_format($row->qty * $row->total_harga, 0, ',', '.') ?></td>

          </tr>
        <?php }
        ?>
        
      </tbody>
    </table>

    <table width="85%" style="font-style: bold">
      <tbody>
        <tr>
          <td width="3%">&nbsp;</td>
          <td width="57%">Total Belanja</td>
          <td width="17%" align="center">=</td>
          <td width="23%" align="right"><b><?= number_format($total, 0, ',', '.') ?></b></td>

        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Tunai</td>
          <td align="center" style="border-bottom: 1px dashed black;">=</td>
          <td align="right" style="border-bottom: 1px dashed black;"><b><?= number_format($bayar, 0, ',', '.') ?></b></td>

        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Kembalian</td>
          <td align="center">=</td>
          <td align="right"><i><?= number_format($kembali, 0, ',', '.') ?></i></td>

        </tr>
      </tbody>
    </table>
    <br>
    Terimakasih Sudah Berbelanja di<br>
	 KSU Asy-Syathibiyyah

</center>
</body>

</html>


<script>
  window.onload = function() {
    window.print();
  };
</script>