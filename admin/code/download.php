<?php
// download.php
session_start();

if (!isset($_SESSION['xml_file_path']) || !file_exists($_SESSION['xml_file_path'])) {
    die("File not found.");
}

header('Content-Description: File Transfer');
header('Content-Type: text/xml'); // Atau "application/xml"
header('Content-Disposition: attachment; filename="exam.xml"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($_SESSION['xml_file_path']));

readfile($_SESSION['xml_file_path']);
exit;
?>
