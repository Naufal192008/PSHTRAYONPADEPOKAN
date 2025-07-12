<?php
require 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM pendaftaran WHERE id = $id");
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Struk Pendaftaran</title>
  <style>
    body { font-family: Arial; }
    .struk { border: 1px solid #000; padding: 20px; width: 400px; margin: auto; }
    button { margin-top: 10px; }
  </style>
</head>
<body>
  <div class="struk">
    <h3>STRUK PENDAFTARAN</h3>
    <p><strong>ID:</strong> <?= $data['id'] ?></p>
    <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <p><strong>Telepon:</strong> <?= htmlspecialchars($data['telepon']) ?></p>
    <p><strong>Tanggal Daftar:</strong> <?= $data['tanggal_daftar'] ?></p>
    
    <button onclick="window.print()">🖨️ Cetak Struk</button>
    <br><br>
    <a href="index.html"><button>Kembali ke Beranda</button></a>
  </div>
</body>
</html>
