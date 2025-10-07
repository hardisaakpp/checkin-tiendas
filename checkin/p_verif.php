<?php
    if($_POST['ced']!=''){ $iden=$_POST['ced']; $tip_iden='1'; }else{ $iden=$_POST['pas']; $tip_iden='2'; }
    include_once "../cx/bd_scs.php";     
    $q = "SELECT COUNT(*) FROM plataforma_web.dbo.chk_cliente AS c WHERE c.chk_txt_iden='$iden' AND c.chk_sts_estado=1"; // coincidencia
    $r = resp_oneval($q);
    if($r==0){
        session_start();
        $_SESSION['id_tienda']=$_POST['id_tienda'];
        $_SESSION['tienda']=$_POST['tienda'];
        $_SESSION['tip_iden']=$tip_iden;
        $_SESSION['iden']=$iden;        
        header("Location: inicio.php"); 
        exit(); 
    }else{ session_destroy(); session_unset(); header("Location: mns_reg.php"); exit(); }
?>