<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string(trim($_POST["name"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $subject = $conn->real_escape_string(trim($_POST["subject"]));
    $message = $conn->real_escape_string(trim($_POST["message"]));
    $sql = "INSERT INTO kontak (nama, email, subjek, pesan, tanggal_kirim) VALUES (?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $to = "info@psht-rayo.org";
            $email_subject = "Pesan Baru dari Website: $subject";
            $email_body = "Anda menerima pesan baru dari website PSHT Rayo Padepokan.\n\n".
                          "Nama: $name\n".
                          "Email: $email\n".
                          "Pesan:\n$message";
            $headers = "From: $email";
            
            mail($to, $email_subject, $email_body, $headers);
            
            header("Location: terima-kasih.html");
            exit();
        } else {
            echo "Terjadi kesalahan. Silakan coba lagi nanti.";
        }
        
        $stmt->close();
    }
    
    $conn->close();
} else {
    header("Location: kontak.html");
    exit();
}
?>