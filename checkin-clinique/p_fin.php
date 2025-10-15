<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if(!isset($_SESSION["id_tienda"]) || !isset($_SESSION["iden"])){ session_unset(); session_destroy(); header("Location: index.php"); exit(); }
    include_once "../cx/bd_scs.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '../assets/lib/PHPMailer/src/Exception.php';
    require '../assets/lib/PHPMailer/src/PHPMailer.php';
    require '../assets/lib/PHPMailer/src/SMTP.php';
    $q = "SELECT COUNT(*) FROM plataforma_web.dbo.chk_cliente_clinique AS c WHERE c.chk_clinique_txt_iden='$_SESSION[iden]' AND c.chk_clinique_sts_estado=1"; // coincidencia tabla CLINIQUE
    $c = resp_oneval($q);
    if($c==0){
        if($_POST['aut']=='on' && $_POST['acep']=='on'){
            $tsql = 'INSERT INTO "plataforma_web"."dbo"."chk_cliente_clinique"
            ("tnd_id", "gen_id", "chk_clinique_txt_nombre", "chk_clinique_txt_apellido", "chk_clinique_val_tip_iden", "chk_clinique_txt_iden", "chk_clinique_txt_correo", "chk_clinique_txt_celular") VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $params = [
                $_SESSION['id_tienda'],
                $_POST['gen_id'],
                $_POST['firstName'],
                $_POST['lastName'],
                $_SESSION['tip_iden'],
                $_SESSION['iden'],
                $_POST['email'],
                $_POST['tel']
            ];
            $r = transac_data_sec($tsql, $params);
            if($r==1){
                $mail = new PHPMailer(true);
                try{ 
                    $mail->isSMTP();       
                    $nombre='CLINIQUE';
                    //$mail->SMTPDebug = 2;
                    $mail->Host = "smtp.office365.com";
                    $mail->Port = 587;
                    $mail->SMTPSecure = 'STARTTLS';
                    $mail->SMTPAuth = true;        
                    $mail->Username ="cs@sunsetcorpholding.com";
                    $mail->Password ="Mabel@2024";
                    $mail->setFrom('cs@sunsetcorpholding.com', 'CLINIQUE');
                    $mail->addAddress("$_POST[email]", "$_POST[firstName] $_POST[lastName]"); 
                    $mail->isHTML(true); 
                    $mail->CharSet = 'UTF-8';
                    $mail->AddEmbeddedImage('../assets/img/promociones/clinique/Estee_Lauder_32_Bits.png', 'promoimg');
                    $mail->Subject = "=?UTF-8?B?".base64_encode("¡Hola " . $_POST['firstName'] . "! Tu registro para el sorteo Clinique | Estée Lauder fue exitoso ✨")."?=";
                    $mail->Body    = '<!DOCTYPE html>
                                    <html lang="es">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <title>Confirmación de Registro</title>
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                line-height: 1.6;
                                                margin: 0;
                                                padding: 0;
                                                background-color: #f4f4f4;
                                            }
                                            .container {
                                                max-width: 600px;
                                                margin: 20px auto;
                                                background-color: #ffffff;
                                                padding: 20px;
                                                border-radius: 8px;
                                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                            }
                                            .header {
                                                text-align: center;
                                                margin-bottom: 20px;
                                            }
                                            h1 {
                                                font-size: 24px;
                                                color: #333;
                                            }
                                            p {
                                                text-align: justify;
                                                font-size: 16px;
                                                color: #555;
                                            }
                                            .footer {
                                                text-align: center;
                                                font-size: 14px;
                                                color: #777;
                                                margin-top: 20px;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="container">
                                            <div class="header">
                                                <h1>¡Hola ' . $_POST['firstName'] . '!</h1>
                                            </div>
                                            <p><strong>Tu registro fue exitoso🎉</strong></p>                                        
                                            <p>Ya estás participando en el sorteo de <strong>Clinique | Estée Lauder</strong>, donde podrás ganar:</p>
                                            <p>👜 1 bolso Estée Lauder</p>
                                            <p>💆‍♀️ 1 crema <strong>Revitalizing Supreme+ Youth Power Creme</strong></p>
                                            <p>📅 El ganador será anunciado el <strong>19 de diciembre de 2025.</strong></p>                                        
                                            <p>Mientras tanto, prepárate para recibir <strong>promociones exclusivas, lanzamientos y novedades</strong> que tenemos para nuestra comunidad.</p>
                                            <p>Revisa aquí los <a href="https://codigoqr.mabelgroup.com.ec/checkin-clinique/visualizador.php?doc=Terminos_y_Condiciones_Clinique" target="_blank" style="color: #007bff; text-decoration: none;"><strong>Términos y Condiciones.</strong></a></p>
                                            <p>Con cariño,</p>
                                            <p><strong>Team Clinique | Estée Lauder</strong></p>
                                            <center><img src="cid:promoimg" alt="Promoción CLINIQUE" style="width: 80%; height: auto; border-radius: 21px;"></center>
                                        </div>
                                    </body>
                                    </html>';
                    $mail->send();       
                    session_unset(); 
                    session_destroy();
                    header("Location: mns_fin.php"); 
                    exit(); 
                }catch(Exception $e){    
                    session_unset(); 
                    session_destroy();
                    $mensaje = "Mensaje no enviado $mail->ErrorInfo, Inténtelo nuevamente"; 
                    print "<script>alert('$mensaje'); window.location= 'Location: inicio.php';</script>";
                    exit(); 
                }
            }else{ session_unset(); session_destroy(); echo'<script type="text/javascript">alert("Error en el envío, por favor intente de nuevo");window.location.href="index.php";</script>'; exit(); }
        }else{ session_unset(); session_destroy(); echo'<script type="text/javascript">alert("No se aceptaron los términos y condiciones, por favor intente de nuevo");window.location.href="index.php";</script>'; exit(); }
    }else{ session_unset(); session_destroy(); echo'<script type="text/javascript">alert("Registro duplicado, usted ya se encuentra registrado, muchas gracias.");window.location.href="mns_reg.php";</script>'; exit(); }
?>