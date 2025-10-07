<?php
//session_start();
//if(!isset($_SESSION['id_tienda']) || !isset($_SESSION['tienda']) || !isset($_SESSION['iden'])){ session_unset(); session_destroy(); header("Location: index.php"); exit(); }
include_once "../cx/bd_scs.php";
//$query=$db->query("SELECT * from canton_ca inner join provincia_pr on canton_ca.CA_ID_Provincia = provincia_pr.PR_ID inner join distrito_dt on distrito_dt.DT_ID_Provincia = provincia_pr.PR_ID where distrito_dt.DT_ID = $_GET[distrito_id]");
/*$q = "SELECT c.id,c.name FROM STORECONTROL.dbo.Ciudad AS c WHERE c.id=$_GET['pro_id']";
$r = resp_simdim($q);
//$states = array();
//while($r=$query->fetch_object()){ $states[]=$r; }
if(count($r)>0){
	print "<option value=''>-- SELECCIONE --</option>";
	foreach($r as $s){ print "<option value='$r[id]'>$r[name]</option>"; }
}elseif(count($r)==0){ print "<option value='0'>NINGUNO</option>"; }
else{ print "<option value=''>-- NO HAY DATOS --</option>"; }*/




		$q = "SELECT c.id,c.name FROM STORECONTROL.dbo.Ciudad AS c WHERE c.id=$_GET[pro_id]";
		$r = resp_simdim($q);
		if($r){
			echo '<option value="">-- SELECCIONE --</option>';
			foreach($r as $c){
				echo "<option value='" . $c['id'] . "'>" . $c['name'] . "</option>";
			}
		}else{
			echo '<option value="">Error en la consulta</option>';
		}
		sqlsrv_close($conn);


?>