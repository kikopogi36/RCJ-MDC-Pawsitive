<?php
include 'includes/session.php';

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $orderid = $_POST['orderid'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];
    
    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("UPDATE tblreturn SET orderid=:orderid, return_reason=:reason, return_status=:status WHERE returnid=:id");
        $stmt->execute(['orderid'=>$orderid, 'reason'=>$reason, 'status'=>$status, 'id'=>$id]);
        $_SESSION['success'] = 'Return updated successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else{
    $_SESSION['error'] = 'Fill up edit return form first';
}

header('location: returns.php');
