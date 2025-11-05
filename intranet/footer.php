      <div class="table-responsive" align="center" style="background-color:#ffffff;"><br>
        <table class="table">
          <thead>
            <tr>
              <?php
                $ruta = '../assets/img/logos/';            
                if(is_dir($ruta)){
                  $files=scandir($ruta);            
                  foreach($files as $file){
                    if($file!=='.' && $file!=='..'){
                      $filePath=$ruta.$file;            
                      $imageExtensions=['jpg','jpeg','png','gif','bmp','webp'];            
                      $fileExtension=strtolower(pathinfo($file, PATHINFO_EXTENSION));            
                      if(in_array($fileExtension, $imageExtensions)){
                        list($width, $height) = getimagesize($filePath);
                        if($width<$height){
                        ?>
                          <img src="<?php echo $filePath; ?>" width="2%" height="2%">
                        <?php
                        }else{
                        ?>                             
                        <img src="<?php echo $filePath; ?>" width="6%" height="6%">
                        <?php
                        }
                      }
                    }
                  }            
                }else{ echo "La carpeta no existe."; }          
              ?>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div align="center"  style="background-color:#64b5f6;">
      <!-- Footer -->
      <footer class="text-center text-white">
        <!-- Copyright -->
        <div class="text-center p-5" style="background-color: rgba(0, 0, 0, 0.2)" id="current_date">
          <script> document.getElementById("current_date").innerHTML = new Date().getFullYear();</script> Mabel Group
        </div>
        <!-- Copyright -->
      </footer>
      <!-- Footer -->
    </div>    
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>