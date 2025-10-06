<?php
// Load database connection
$host = "localhost";  // Ganti sesuai konfigurasi database
$user = "root";       // Ganti sesuai konfigurasi database
$pass = "123";           // Ganti sesuai konfigurasi database
$dbname = "db_koperasi"; // Ganti dengan nama database
$port = '3310';

$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data kategori dari tbl_kateg_trans
$sql = "SELECT id FROM tbl_kateg_trans";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $periode_sekarang = date('my'); // Format MMYY (contoh: "0325" untuk Maret 2025)

    while ($row = $result->fetch_assoc()) {
        $kategori_keuangan = $row['id'];

        // Cek apakah sudah ada data untuk periode saat ini
        $cek_sql = "SELECT nominal FROM tbl_keuangan WHERE kategori_keuangan = '$kategori_keuangan' AND periode = '$periode_sekarang'";
        $cek_result = $conn->query($cek_sql);

        if ($cek_result->num_rows == 0) {
            // Ambil nominal dari periode sebelumnya
            $periode_sebelumnya = date('my', strtotime("-1 month"));
            $prev_sql = "SELECT nominal FROM tbl_keuangan WHERE kategori_keuangan = '$kategori_keuangan' AND periode = '$periode_sebelumnya' ORDER BY id DESC LIMIT 1";
            $prev_result = $conn->query($prev_sql);

            if ($prev_result->num_rows > 0) {
                $prev_row = $prev_result->fetch_assoc();
                $nominal = $prev_row['nominal']; // Ambil nominal dari periode sebelumnya
            } else {
                $nominal = 0; // Default nominal 0 jika tidak ada data sebelumnya
            }

            // Insert data ke tbl_keuangan
            $insert_sql = "INSERT INTO tbl_keuangan (kategori_keuangan, nominal, periode) VALUES ('$kategori_keuangan', '$nominal', '$periode_sekarang')";
            $conn->query($insert_sql);
        }
    }
}

// Tutup koneksi database
$conn->close();

echo "Insert ke tbl_keuangan selesai!";
?>
