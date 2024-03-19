<?php
include("php/dbconnect.php");
$numPagos = 0;
if (isset($_POST['studentID'])) {
    $id = mysqli_real_escape_string($conn, $_POST['studentID']);
    $sql = "select COUNT(*) AS total_registros from fees_transaction where stdid = '$id'";
    $sq = $conn->query($sql);
    $sr = $sq->fetch_assoc();
    $numPagos = $sr['total_registros'] > 0 ? $sr['total_registros'] : 0;
  }
// Devolver el número de pagos como respuesta a la llamada AJAX
echo $numPagos;
?>