      <style>
        .logos-section {
          background: #ffffff !important;
          padding: 3rem 0;
          border-radius: 16px 16px 0 0;
          box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
          overflow: hidden;
          position: relative;
        }

        .logos-section::before,
        .logos-section::after {
          content: '';
          position: absolute;
          top: 0;
          width: 120px;
          height: 100%;
          z-index: 2;
          pointer-events: none;
        }

        .logos-section::before {
          left: 0;
          background: linear-gradient(to right, #ffffff, rgba(255, 255, 255, 0));
        }

        .logos-section::after {
          right: 0;
          background: linear-gradient(to left, #ffffff, rgba(255, 255, 255, 0));
        }

        .logos-carousel-wrapper {
          position: relative;
          width: 100%;
          overflow: hidden;
        }

        .logos-track {
          display: flex;
          align-items: center;
          gap: 3rem;
          animation: scroll 30s linear infinite;
          will-change: transform;
          width: fit-content;
        }

        .logos-track:hover {
          animation-play-state: paused;
        }

        @keyframes scroll {
          from {
            transform: translateX(0);
          }
          to {
            transform: translateX(calc(-100% / 2));
          }
        }

        /* Asegurar que la animación sea suave sin saltos */
        .logos-track {
          animation-timing-function: linear;
        }

        .logo-item {
          flex-shrink: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 0 1rem;
          transition: transform 0.3s ease;
        }

        .logo-footer {
          max-height: 60px;
          width: auto;
          object-fit: contain;
          filter: drop-shadow(0 2px 8px rgba(0,0,0,0.1));
          transition: transform 0.3s ease, filter 0.3s ease;
          cursor: pointer;
        }

        .logo-footer:hover {
          transform: scale(1.15);
          filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));
        }

        .logo-duplicate {
          display: flex;
          align-items: center;
          gap: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
          .logos-track {
            gap: 2rem;
            animation-duration: 20s;
          }
          
          .logo-item {
            padding: 0 0.5rem;
          }
          
          .logo-footer {
            max-height: 50px;
          }
        }
      </style>
      <div class="logos-section">
        <div class="container-fluid px-0">
          <div class="logos-carousel-wrapper">
            <div class="logos-track">
              <?php
                $ruta = '../assets/img/logos/';            
                if(is_dir($ruta)){
                  $files=scandir($ruta);
                  $logos = [];
                  foreach($files as $file){
                    if($file!=='.' && $file!=='..'){
                      $filePath=$ruta.$file;            
                      $imageExtensions=['jpg','jpeg','png','gif','bmp','webp'];            
                      $fileExtension=strtolower(pathinfo($file, PATHINFO_EXTENSION));            
                      if(in_array($fileExtension, $imageExtensions)){
                        list($width, $height) = getimagesize($filePath);
                        $logos[] = [
                          'path' => $filePath,
                          'isPortrait' => $width < $height
                        ];
                      }
                    }
                  }
                  
                  // Duplicar logos exactamente 2 veces para movimiento continuo perfecto
                  // La animación se mueve -50%, lo que hace que cuando termine, el siguiente
                  // conjunto sea idéntico al primero, creando un bucle infinito sin saltos
                  for($i = 0; $i < 2; $i++) {
                    foreach($logos as $logo) {
                      $size = $logo['isPortrait'] ? 'width="60" height="auto"' : 'width="120" height="auto"';
                      echo '<div class="logo-item">';
                      echo '<img src="' . htmlspecialchars($logo['path']) . '" ' . $size . ' class="logo-footer" alt="Logo">';
                      echo '</div>';
                    }
                  }
                }else{ 
                  echo '<div class="col-12 text-center text-muted">La carpeta no existe.</div>'; 
                }          
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer-modern" style="background: linear-gradient(135deg, #64b5f6 0%, #1976d2 100%); box-shadow: 0 -4px 20px rgba(0,0,0,0.15);">
      <div class="container">
        <div class="text-center text-white py-4">
          <div class="footer-content" style="padding: 1.5rem 0;">
            <p class="mb-2" style="font-size: 1rem; font-weight: 500; opacity: 0.95;">
              © <span id="current_date"></span> Mabel Group
            </p>
            <p class="mb-0" style="font-size: 0.875rem; opacity: 0.8;">
              Sistema Intranet - Todos los derechos reservados
            </p>
          </div>
        </div>
      </div>
      <script> 
        document.getElementById("current_date").innerHTML = new Date().getFullYear();
        
        // Asegurar movimiento continuo perfecto del carrusel
        document.addEventListener('DOMContentLoaded', function() {
          const track = document.querySelector('.logos-track');
          if (track) {
            // Esperar a que las imágenes se carguen para calcular correctamente
            const images = track.querySelectorAll('img');
            let loadedImages = 0;
            
            const calculateAnimation = function() {
              // Calcular el ancho exacto de la mitad del track
              const trackWidth = track.scrollWidth;
              const halfWidth = trackWidth / 2;
              
              // Crear o actualizar la animación con el valor exacto en píxeles
              let style = document.getElementById('logos-scroll-animation');
              if (!style) {
                style = document.createElement('style');
                style.id = 'logos-scroll-animation';
                document.head.appendChild(style);
              }
              
              style.textContent = `
                @keyframes scroll {
                  from {
                    transform: translateX(0);
                  }
                  to {
                    transform: translateX(-${halfWidth}px);
                  }
                }
              `;
            };
            
            if (images.length === 0) {
              calculateAnimation();
            } else {
              images.forEach(function(img) {
                if (img.complete) {
                  loadedImages++;
                  if (loadedImages === images.length) {
                    setTimeout(calculateAnimation, 100);
                  }
                } else {
                  img.addEventListener('load', function() {
                    loadedImages++;
                    if (loadedImages === images.length) {
                      setTimeout(calculateAnimation, 100);
                    }
                  });
                }
              });
              
              // Fallback si todas las imágenes ya están cargadas
              if (loadedImages === images.length) {
                setTimeout(calculateAnimation, 100);
              }
            }
          }
        });
      </script>
    </footer>    
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>