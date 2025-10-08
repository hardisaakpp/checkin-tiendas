<?php 
  session_start();
  if(!isset($_SESSION['id_tienda']) || !isset($_SESSION['tienda']) || !isset($_SESSION['iden'])){ session_unset(); session_destroy(); header("Location: index.php"); exit(); }
  $tienda=$_SESSION['tienda'];
  include_once "header.php"; 
  include_once "../cx/bd_scs.php";  
  $q = "SELECT gen_id, gen_txt_nombre FROM plataforma_web.dbo.chk_genero;";
  $r = resp_simdim($q);
?>

      <main>        
        <div class="row g-2">
          <h4><strong>Ingresa tu información</strong></h4>
          <form class="needs-validation" validate action="p_fin.php" method="post">
            <div class="row g-3">
              <div class="col-12">
                <label for="username" class="form-label">Número de identificación</label>
                <div class="input-group has-validation">
                  <h4><?php echo $_SESSION['iden']; ?></h4>&emsp;&emsp;&emsp;
                  <a href="index.php?MG$=ABC2880<?php echo $_SESSION['id_tienda']; ?>?809ID=465">
                    <button type="button" class="btn btn-secondary btn-sm btn-single-click">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                      </svg>
                    </button>
                  </a><br><br>
                </div>
              </div>
              <div class="col-12">
                <label for="firstName" class="form-label">Nombres</label>
                <input style="text-transform: uppercase;" type="text" class="form-control" name="firstName" id="firstName" placeholder="" value="" autocapitalize="characters" minlength="3" maxlength="100" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras y un espacio entre palabras" required>
                <div class="invalid-feedback">
                  Nombres válidos son requeridos.
                </div>
              </div>

              <div class="col-12">
                <label for="lastName" class="form-label">Apellidos</label>
                <input style="text-transform: uppercase;" type="text" class="form-control" name="lastName" id="lastName" placeholder="" value="" autocapitalize="characters" minlength="3" maxlength="100" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras y un espacio entre palabras" required>
                <div class="invalid-feedback">
                  Apellidos válidos son requeridos.
                </div>
              </div>

              <div class="col-12">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="tu@ejemplo.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Por favor ingrese un correo electrónico válido. Ejemplo: tu@ejemplo.com" required>
                <div class="invalid-feedback">
                  Ingrese una dirección de correo electrónico válida para recibir actualizaciones de envío.
                </div>
              </div>

              <div class="col-12">
                <label for="address" class="form-label">Teléfono Celular</label>
                <input type="text" class="form-control" name="tel" id="tel" minlength="10" maxlength="10" pattern="\d*" title="Solo se permiten números, 10 digitos" required>
                <div class="invalid-feedback">
                  Por favor ingrese su número de teléfono celular.
                </div>
              </div>

              <div class="col-12">
                <label for="gen_id">Género </label>
                <select id="gen_id" class="form-control" name="gen_id" required>
                  <option value="" selected="selected">--- Seleccione ---</option>
                  <?php foreach($r as $c){?>
                  <option value="<?php echo $c['gen_id']; ?>"><?php echo $c['gen_txt_nombre']; ?></option>
                  <?php } ?>
                </select>	
              </div>

            </div>
            <hr class="my-4">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="aut" id="aut" required>
              <label class="form-check-label" for="same-address">Autorizo el uso y almacenamiento de mis datos personales conforme a la Ley de Protección de Datos Personales de Ecuador a COSMEC</label>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="acep" id="acep" required>
              <label class="form-check-label" for="save-info">Acepto recibir correo publicitario de COSMEC</label>
            </div>
            <hr class="my-4">
            <button class="w-100 btn btn-lg" style="background-color: #59595C; color: white;" type="submit" data-loading="spinner" data-loading-text="Finalizando...">Finalizar</button><br><br>
            <button class="w-100 btn btn-secondary btn-lg" type="reset" data-loading-duration="800">Limpiar formulario</button>
            <hr class="my-4">
            <div class="card" align="justify">
              <small class="text-body-secondary" align="center"><strong>Ley de Protección de datos personales</strong> En Ecuador el artículo 66, numeral 19 de la Constitución de la República, establece “el derecho a la protección de datos de carácter personal, 
                que incluye el acceso y la decisión sobre información y datos de este carácter, así como su correspondiente protección. 
                La recolección, archivo, procesamiento, distribución o difusión de estos datos o información requerirán la autorización del titular o el mandato de la ley”.
              </small>
            </div>
            <hr class="my-4">
          </form>
        </div>
      </main>

      <script>
        document.getElementById('email').addEventListener('input', function() {
          const email = this.value;
          const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          // Validación de que el correo tenga al menos un punto en el dominio
          if (emailRegex.test(email) && email.includes('.') && email.split('@')[1].includes('.')) {
            this.setCustomValidity('');
          } else {
            this.setCustomValidity('Por favor ingresa un correo electrónico válido con un punto en el dominio y el dominio.');
          }
        });

        const form = document.querySelector('form');
        const pasaporteInput1 = document.getElementById('firstName');
        const pasaporteInput2= document.getElementById('lastName');
        form.addEventListener('submit', function(event) {
          if (pasaporteInput1 && pasaporteInput1.value && pasaporteInput2 && pasaporteInput2.value) {
            pasaporteInput1.value = pasaporteInput1.value.toUpperCase();
            pasaporteInput2.value = pasaporteInput2.value.toUpperCase();
          }
        });
      </script>

<?php include_once "footer.php"; ?>