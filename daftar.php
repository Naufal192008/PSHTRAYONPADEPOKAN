<?php
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $required = ['nama', 'email', 'telepon', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'];
        $missing = array_diff($required, array_keys($_POST));

        if (!empty($missing)) {
            throw new Exception('Data tidak lengkap. Field yang wajib diisi: ' . implode(', ', $missing));
        }

        $nama = trim($_POST["nama"]);
        $email = trim($_POST["email"]);
        $telepon = trim($_POST["telepon"]);
        $alamat = trim($_POST["alamat"]);
        $tempat_lahir = trim($_POST["tempat_lahir"]);
        $tanggal_lahir = trim($_POST["tanggal_lahir"]);
        $jenis_kelamin = trim($_POST["jenis_kelamin"]);
        $pekerjaan = isset($_POST["pekerjaan"]) ? trim($_POST["pekerjaan"]) : null;
        $alasan = isset($_POST["alasan"]) ? trim($_POST["alasan"]) : null;
        $persetujuan = isset($_POST["persetujuan"]) ? 1 : 0;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid');
        }

        $check = db_query("SELECT id FROM pendaftaran WHERE email = ?", [$email]);
        if ($check && $check->get_result()->num_rows > 0) {
            throw new Exception('Email sudah terdaftar. Gunakan email lain.');
        }

        $sql = "INSERT INTO pendaftaran (
            nama, email, telepon, alamat, tempat_lahir, 
            tanggal_lahir, jenis_kelamin, pekerjaan, alasan,
            persetujuan, tanggal_daftar
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = db_query($sql, [
            $nama, $email, $telepon, $alamat, $tempat_lahir,
            $tanggal_lahir, $jenis_kelamin, $pekerjaan, $alasan,
            $persetujuan
        ]);

        if (!$stmt) {
            throw new Exception('Gagal menyimpan data pendaftaran');
        }

        $last_id = $stmt->insert_id;
        $stmt->close();

        header("Location: sukses.php?id=$last_id");
        exit;

    } catch (Exception $e) {
        echo "<div style='color: red; font-family: sans-serif; padding: 15px;'><strong>Gagal:</strong> ".$e->getMessage()."</div>";
        echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Kembali</a>";
    }
} else {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h2>Form Pendaftaran</h2>
    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap *</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Telepon *</label>
            <input type="text" name="telepon" required>
        </div>
        <div class="form-group">
            <label>Alamat *</label>
            <textarea name="alamat" required></textarea>
        </div>
        <div class="form-group">
            <label>Tempat Lahir *</label>
            <input type="text" name="tempat_lahir" required>
        </div>
        <div class="form-group">
            <label>Tanggal Lahir *</label>
            <input type="date" name="tanggal_lahir" required>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin *</label>
            <select name="jenis_kelamin" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pekerjaan</label>
            <input type="text" name="pekerjaan">
        </div>
        <div class="form-group">
            <label>Alasan Bergabung</label>
            <textarea name="alasan"></textarea>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="persetujuan" required> Saya setuju dengan syarat dan ketentuan</label>
        </div>
        <button type="submit">Daftar</button>
    </form>
</body>
</html>
<?php } ?>
