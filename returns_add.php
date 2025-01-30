<?php
include 'includes/session.php';

if(isset($_POST['add'])){
    $orderid = $_POST['orderid'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    
    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("INSERT INTO tblreturn (orderid, return_reason, return_status, return_date, staff_id) VALUES (:orderid, :reason, :status, NOW(), :staff_id)");
        $stmt->execute(['orderid'=>$orderid, 'reason'=>$reason, 'status'=>$status, 'staff_id'=>$_SESSION['admin']]);
        $_SESSION['success'] = 'Return added successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else{
    $_SESSION['error'] = 'Fill up return form first';
}

header('location: returns.php');
