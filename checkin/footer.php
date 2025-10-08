      <div align="center">
        <footer class="text-center text-white" style="background-color: #000000">
          <div class="container">
            <section class="mt-2">
              <div class="row text-center d-flex justify-content-center pt-5">
                <ul class="list-inline">
                  <li class="list-inline-item"><a href='visualizador.php?MG$=ABC28801?809ID=465' target="_blank">Aviso de Privacidad</a></li>
                  <li class="list-inline-item"><a href="https://www.roland.ec/crazygiveaway" target="_blank">Términos y Condiciones</a></li>
                  <li class="list-inline-item"><a href="mailto:info@roland.ec">Soporte</a></li>
                </ul>
              </div>
            </section>
          </div>
          
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)" id="current_date">
              <script> document.getElementById("current_date").innerHTML = new Date().getFullYear();</script> <a class="text-white" href="https://www.roland.ec/" target="_blank">Roland</a>
          </div>
        </footer>
      </div>
    </div>
    
    <!-- Loading Overlay Global -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">
            Procesando tu solicitud...<br>
            Por favor espera un momento
        </div>
    </div>
    
    <link rel="stylesheet" href="../assets/dist/css/checkin-loading.css?v=1.2">
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <?php if(isset($need_form_validation) && $need_form_validation === true): ?>
    <script src="../assets/dist/js/checkout.js"></script>
    <?php endif; ?>
    <script src="../assets/dist/js/loading-manager.min.js?v=2.1"></script>
  </body>
</html>