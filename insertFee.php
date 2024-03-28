<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';

if (isset($_POST['save'])) {
  $paid = mysqli_real_escape_string($conn, $_POST['paid']);
  $submitdate = mysqli_real_escape_string($conn, $_POST['submitdate']);
  $transcation_remark = mysqli_real_escape_string($conn, $_POST['transaction_remark']);
  $sid = mysqli_real_escape_string($conn, $_POST['sid']);

  $sql = "select fees,balance  from student where id = '$sid'";
  $sq = $conn->query($sql);
  $sr = $sq->fetch_assoc();
  $totalfee = $sr['fees'];
  $last_id = '';
  if ($sr['balance'] > 0) {
    $sql = "insert into fees_transaction(stdid,submitdate,transcation_remark,paid) values('$sid','$submitdate','$transcation_remark','$paid') ";
    if ($conn->query($sql) === TRUE) {
      $last_id = $conn->insert_id;
    } else {
      $last_id = $conn->error;
    }
    $sql = "SELECT sum(paid) as totalpaid FROM fees_transaction WHERE stdid = '$sid'";
    $tpq = $conn->query($sql);
    $tpr = $tpq->fetch_assoc();
    $totalpaid = $tpr['totalpaid'];
    $tbalance = $totalfee - $totalpaid;

    $sql = "update student set balance='$tbalance' where id = '$sid'";

    $conn->query($sql);
    echo $last_id;
  }
} else {
  error_log('Error en el parametro save');
}
