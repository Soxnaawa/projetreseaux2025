<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["documentFile"])) {
    // Configuration FTP
    $ftp_server = "192.168.1.186";
    $ftp_user   = "ftpuser";
    $ftp_pass   = "passer";
    $remote_dir = "/ftpuser/ftp/";
    
    $local_file = $_FILES["documentFile"]["tmp_name"];
    $file_name  = basename($_FILES["documentFile"]["name"]);
    $remote_file_url = "ftp://$ftp_user:$ftp_pass@$ftp_server$remote_dir" . $file_name;
    
    $fp = fopen($local_file, "r");
    if (!$fp) {
        die("Impossible d'ouvrir le fichier local.");
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_file_url);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($local_file));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    $error  = curl_error($ch);
    curl_close($ch);
    fclose($fp);
    
    if ($result === false) {
        echo "Erreur d'upload : " . $error;
    } else {
        echo "Upload réussi !";
    }
} else {
    echo "Aucun fichier reçu.";
}
?>
