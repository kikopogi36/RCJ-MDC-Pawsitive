<?php
include 'includes/session.php';

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    
    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("DELETE FROM tblreturn WHERE returnid=:id");
        $stmt->execute(['id'=>$id]);
        $_SESSION['success'] = 'Return deleted successfully';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else{
    $_SESSION['error'] = 'Select return to delete first';
}

header('location: returns.php');
