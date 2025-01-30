<?php 
require_once("../include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/login.php");
} 

$content = isset($_GET['page']) ? $_GET['page'] : 'home';
if($content == 'home' || $content == '') {
    redirect("products/");
}

require_once("theme/templates.php");
?>