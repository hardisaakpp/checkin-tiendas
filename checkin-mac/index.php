<?php
session_destroy();
session_unset();
$id_tienda_string = $_GET['MG$'];
if (!isset($id_tienda_string) || $id_tienda_string == '' || is_null($id_tienda_string)) {
  session_unset();
  session_destroy();
  header("Location: mns_check.php");
  exit();
}
$id_tienda = substr($id_tienda_string, 7, -10);
$ix = 1; //identifica que es un index
include_once "../cx/bd_scs.php";
$q = "SELECT TOP 1 a.nombre2,c.name FROM STORECONTROL.dbo.Almacen AS a INNER JOIN STORECONTROL.dbo.Emp AS e ON a.fk_emp=e.id INNER JOIN STORECONTROL.dbo.Ciudad AS c ON a.id_ciud=c.id WHERE a.sts_nombre2=1 AND a.id=$id_tienda;";
$r = resp_onedim($q);
if (empty($r)) {
  session_unset();
  session_destroy();
  header("Location: mns_check.php");
  exit();
}
$tienda = $r[0]['nombre2'];
$tsql = 'INSERT INTO "plataforma_web"."dbo"."log_chk_mac" ("tnd_id")' . "VALUES ($id_tienda);";
transac_data($tsql);
$need_form_validation = true; // Habilitar validación de formularios
include_once "header.php";
?>

<main>
  <a href="#">
    <img src="../assets/img/promociones/mac/KIT-COLOUR-EXCESS.png" class="d-block w-100" alt="Promoción 1" style="width: 50%; height: auto; border-radius: 21px;">
  </a><br>
  <h4 class="d-flex justify-content-between align-items-center mb-3">
    <center><strong>Ingresa tu número de identificación</strong></center>
  </h4>
  <form class="needs-validation" validate action="p_verif.php" method="post">
    <!-- Cédula -->
    <div id="input1" class="mb-3">
      <label for="ced" class="form-label">Cédula:</label>
      <input type="text" class="form-control" name="ced" id="ced" minlength="10" maxlength="10" pattern="\d{10}" title="Solo se permiten 10 números">
    </div>

    <!-- Pasaporte -->
    <div id="input2" class="mb-3 d-none">
      <label for="pas" class="form-label">Pasaporte:</label>
      <input style="text-transform: uppercase;" type="text" class="form-control" name="pas" id="pas" minlength="6" maxlength="10" pattern="[A-Za-z0-9]{6,10}" title="Solo letras y números (6-10 caracteres)">
    </div>
    <!-- Switch -->
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="toggleSwitch">
      <label class="fs-smaller" for="toggleSwitch" id="toggleLabelCedula"><i>Cambiar a pasaporte</i></label>
      <label class="fs-smaller d-none" for="toggleSwitch" id="toggleLabelPasaporte"><i>Cambiar a cédula</i></label>
    </div>
    <center><button class="w-80 btn btn-secondary btn-lg" type="submit" data-loading="spinner" data-loading-text="Participando...">
        Participar
      </button></center>
    <input name="id_tienda" type="hidden" value="<?php echo $id_tienda; ?>">
    <input name="tienda" type="hidden" value="<?php echo $tienda; ?>">
  </form>
</main><br>

<div class="text-center overflow-hidden"
  style="border-radius: 15px; background-color: #59595C; color: white; display: flex; align-items: center; justify-content: center; text-align: center; padding: 2rem; min-height: 100px;">
  <p style="margin: 0;">No te pierdas nuestras <strong>NOVEDADES</strong>, <strong>PROMOCIONES</strong> Y <strong>DESCUENTOS EXCLUSIVOS.</strong></p>
</div>

<!-- Script -->
<script>
  const toggleSwitch = document.getElementById('toggleSwitch');
  const labelCedula = document.getElementById('toggleLabelCedula');
  const labelPasaporte = document.getElementById('toggleLabelPasaporte');
  const inputCed = document.getElementById('input1');
  const inputPas = document.getElementById('input2');
  const ced = document.getElementById('ced');
  const pas = document.getElementById('pas');

  // Inicialmente, solo la cédula está visible y requerida
  ced.setAttribute('required', 'required');
  pas.removeAttribute('required');

  toggleSwitch.addEventListener('change', () => {
    if (toggleSwitch.checked) {
      // Mostrar pasaporte, ocultar cédula
      inputCed.classList.add('d-none');
      inputPas.classList.remove('d-none');
      ced.removeAttribute('required');
      pas.setAttribute('required', 'required');
      ced.value = ''; // Limpiar cédula
      labelCedula.classList.add('d-none');
      labelPasaporte.classList.remove('d-none');
    } else {
      // Mostrar cédula, ocultar pasaporte
      inputPas.classList.add('d-none');
      inputCed.classList.remove('d-none');
      pas.removeAttribute('required');
      ced.setAttribute('required', 'required');
      labelCedula.classList.remove('d-none');
      labelPasaporte.classList.add('d-none');
      pas.value = ''; // Limpiar pasaporte
    }
  });

  // El sistema de carga ahora se maneja automáticamente por loading-manager.js
</script>
<?php include_once "footer.php"; ?>