<?php 
    include_once 'header.php'; 
    $id_doc_string = $_GET['MG$']; 
    $id_doc = substr($id_doc_string, 7, -10);
?>
    <main>
        <head>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            <style>
                /* Impide la selección de texto */
                body {
                    user-select: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                }

                #pdf-container {
                    position: relative;
                    overflow: hidden;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-top: 20px;
                    width: 100%;
                }

                #pdf-canvas {
                    max-width: 100%;
                    height: auto;
                    display: block;
                }

                #overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(255, 255, 255, 0.5);
                    z-index: 2;
                }

                /* Bloquear clic derecho */
                body {
                    -webkit-touch-callout: none; /* Safari */
                    -webkit-user-select: none; /* Safari */
                    -khtml-user-select: none; /* Konqueror */
                    -moz-user-select: none; /* Firefox */
                    -ms-user-select: none; /* Internet Explorer/Edge */
                    user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera, and Edge */
                }

                /* Estilo para las páginas del PDF */
                .pdf-page {
                    margin-bottom: 20px;
                }

                /* Estilo para el paginador */
                .pagination-container {
                    text-align: center;
                    margin-top: 20px;
                }

                /* Botones de zoom en el centro del lado derecho */
                .zoom-buttons {
                    position: fixed;
                    right: 20px;
                    top: 50%;
                    transform: translateY(-50%);
                    z-index: 10;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .zoom-buttons button {
                    margin: 10px 0;
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    font-size: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                /* Estilo del icono en los botones */
                .zoom-buttons button i {
                    color: #ffff;
                }

                .color-num {
                    color:rgb(69, 96, 170);
                }
            </style>
        </head>
        
        <div class="container my-4">
            <div id="pdf-container">
                <div id="overlay"></div>
                <canvas id="pdf-canvas" class="pdf-page"></canvas>
            </div>
            <div class="pagination-container">
                <button id="prev-page" class="btn btn-primary">Anterior</button>
                <span class="color-num" id="page-num"></span> <span class="color-num">/</span> <span class="color-num" id="page-count"></span>
                <button id="next-page" class="btn btn-primary">Siguiente</button>
            </div>
        </div>
        <div class="zoom-buttons">
            <button id="zoom-in" class="btn btn-secondary"><i class="fas fa-search-plus"></i></button>
            <button id="zoom-out" class="btn btn-secondary"><i class="fas fa-search-minus"></i></button>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
        <script>
            const url = '../assets/doc/<?php echo $id_doc ?>.pdf';
            let pdfDoc = null;
            let currentPage = 1;
            let totalPages = 0;
            let zoomScale = 1.0; // Controla el zoom
            let baseWidth = null; // Ancho original de la página PDF sin escalar

            const canvas = document.getElementById('pdf-canvas');
            const context = canvas.getContext('2d');

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdfDoc = pdf;
                totalPages = pdfDoc.numPages;
                document.getElementById('page-count').textContent = totalPages;
                renderPage(currentPage);
            });

            function renderPage(num) {
                pdfDoc.getPage(num).then(function(page) {
                    // Detecta ancho del contenedor
                    const containerWidth = document.getElementById('pdf-container').clientWidth;

                    // Obtener ancho original de la página
                    const unscaledViewport = page.getViewport({ scale: 1 });
                    baseWidth = unscaledViewport.width;

                    // Escala base + zoom del usuario
                    const scale = (containerWidth / baseWidth) * zoomScale;
                    const viewport = page.getViewport({ scale: scale });

                    // Ajustar canvas
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(function() {
                        document.getElementById('page-num').textContent = num;
                    });
                });
            }

            // Navegación entre páginas
            document.getElementById('prev-page').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderPage(currentPage);
                }
            });

            document.getElementById('next-page').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPage(currentPage);
                }
            });

            // Zoom
            document.getElementById('zoom-in').addEventListener('click', function() {
                if (zoomScale < 3) {
                    zoomScale += 0.2;
                    renderPage(currentPage);
                }
            });

            document.getElementById('zoom-out').addEventListener('click', function() {
                if (zoomScale > 0.4) {
                    zoomScale -= 0.2;
                    renderPage(currentPage);
                }
            });
        </script>      
    </main>
<?php include_once 'footer.php'; ?>