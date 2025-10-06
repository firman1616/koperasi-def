<?php
// Konfigurasi database
$host = 'localhost'; // Sesuaikan dengan host database kamu
$user = 'root'; // Sesuaikan dengan user database kamu
$pass = '123'; // Sesuaikan dengan password database kamu
$db   = 'db_koperasi'; // Sesuaikan dengan nama database kamu
$port = '3310';

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db, $port);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// die();

// Ambil data anggota yang belum memiliki iuran
$sql = "SELECT id FROM tbl_anggota WHERE id NOT IN (SELECT DISTINCT anggota_id FROM tbl_iuran)";
$result = $conn->query($sql);

$totalAnggota = $result->num_rows;
$currentAnggota = 0;

if ($totalAnggota > 0) {
    echo '<script>
        function updateProgress(percent) {
            document.getElementById("progress-bar").style.width = percent + "%";
            document.getElementById("progress-text").innerText = percent + "%";
        }
    </script>';
    echo '<div style="width: 100%; background: #ddd; border: 1px solid #aaa; padding: 3px; margin-bottom: 10px;">
            <div id="progress-bar" style="width: 0%; height: 20px; background: green;"></div>
          </div>
          <p id="progress-text">0%</p>';
    
    // Loop untuk setiap anggota baru
    while ($row = $result->fetch_assoc()) {
        $anggota_id = $row['id'];
        
        // Lewati anggota dengan ID 117
        if ($anggota_id == 117) {
            continue;
        }
        
        $nominal = 200000;
        $date = date('Ymd h:i:s');
        
        // Insert untuk setiap bulan dari Januari hingga Desember
        for ($month = 1; $month <= 12; $month++) {
            $periode = sprintf('%02d', $month) . date('y'); // Format mmYY
            
            $insertSQL = "INSERT INTO tbl_iuran (anggota_id, nominal, date, periode) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertSQL);
            $stmt->bind_param("iiss", $anggota_id, $nominal, $date, $periode);
            $stmt->execute();
        }
        
        $currentAnggota++;
        $progress = intval(($currentAnggota / $totalAnggota) * 100);
        echo "<script>updateProgress($progress);</script>";
        ob_flush();
        flush();
    }
    echo "<script>updateProgress(100);</script>";
    echo "Data iuran berhasil dimasukkan untuk anggota baru.";
} else {
    echo "Tidak ada anggota baru yang perlu dimasukkan.";
}

// Tutup koneksi
$conn->close();
?>
