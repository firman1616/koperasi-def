<table class="table table-bordered" id="tableIuran" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Anggota</th>
      <th>Simpanan Pokok</th>
      <th>Simpanan Wajib</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $x = 1;
    $tahun = date('y');
    $bulanSekarang = date('n');
    foreach ($iuran as $row) { ?>
      <tr>
        <td><?= $x++; ?></td>
        <td><?= $row->name ?></td>
        <td> <?php if ($row->deposit_status != 1) : ?>
            <button type="button" class="btn btn-info deposit-btn"
              data-toggle="modal"
              data-target="#depositModal"
              data-id="<?= $row->id ?>">
              Simpanan
            </button>
          <?php else : ?>
            <span class="badge badge-success">Lunas</span>
          <?php endif; ?>
        </td>
        <td>
          <?php
          $periodeStart = 1124; // Periode awal: November 2024 (MMYY)
          $bulanStart = 11; // November
          $tahunStart = 2024; // Tahun 2024
          $tahunSekarang = date('Y'); // Tahun saat ini
          $bulanSekarang = date('n'); // Bulan saat ini

          // Loop dari periode 1124 hingga bulan saat ini
          $periode = $periodeStart;
          while (true):
            $bulan = (int) substr($periode, 0, 2); // Ambil MM
            $tahun = (int) ('20' . substr($periode, 2, 2)); // Ambil YYYY
            if ($tahun > $tahunSekarang || ($tahun == $tahunSekarang && $bulan > $bulanSekarang)) {
              break;
            }
            $status = isset($row->iuran_status[$periode]) ? $row->iuran_status[$periode] : 0;

            if ($status != 1): ?>
              <button type="button" class="btn btn-primary iuran-btn"
                title="Bayar Iuran <?= $periode ?>"
                data-id="<?= $row->id ?>"
                data-periode="<?= $periode ?>">
                <i class="fa fa-coins"></i> <?= $periode ?>
              </button>
          <?php endif;

            $bulan++;
            if ($bulan > 12) {
              $bulan = 1;
              $tahun++;
            }
            $periode = sprintf('%02d', $bulan) . substr($tahun, 2, 2); // Format MMYY
          endwhile; ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="iuranModal" tabindex="-1" aria-labelledby="iuranModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="iuranModalLabel">Pembayaran Iuran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="periode" class="form-label">Periode</label>
            <input type="text" class="form-control" id="periode" readonly>
          </div>
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal & Waktu</label>
            <input type="date" class="form-control" id="tanggal">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary iuran">Bayar</button>
      </div>
    </div>
  </div>
</div>


<!-- deposit -->
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="depositModalLabel">Deposit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="depositForm">
          <!-- <input type="hidden" id="anggota_id" name="anggota_id" value="123"> -->
          <div class="form-group">
            <label for="depositAmount">Jumlah Deposit</label>
            <input type="number" class="form-control" id="depositAmount" name="depositAmount" value="500000">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="submitDeposit">Submit</button>
      </div>
    </div>
  </div>
</div>