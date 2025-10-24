      <div align="center">
        <footer class="text-center text-white" style="background-color: #000000">
          <div class="container">
            <section class="mt-2">
              <div class="row text-center d-flex justify-content-center pt-5">
                <ul class="list-inline">
                  <li class="list-inline-item"><a href='visualizador.php?doc=Aviso_de_Privacidad_Clinique' target="_blank">Aviso de Privacidad</a></li>
                  <li class="list-inline-item"><a href='visualizador.php?doc=Terminos_y_Condiciones_Clinique' target="_blank">Términos y Condiciones</a></li>
                </ul>
              </div>
            </section>
          </div>

          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)" id="current_date">
            <script>
              document.getElementById("current_date").innerHTML = new Date().getFullYear();
            </script>
            <a class="text-white" href="javascript:void(0)">CLINIQUE | ESTÉE LAUDER</a>
          </div>
        </footer>
      </div>
      </div>


      <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
      <?php if (isset($need_form_validation) && $need_form_validation === true): ?>
        <script src="../assets/dist/js/checkout.js"></script>
      <?php endif; ?>
      </body>

      </html>