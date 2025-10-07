<?php  
  session_destroy(); session_unset();  
  $m=1;
  include_once "header.php";
?>

    <main> 
        <br>       
        <div class="container" align='center'>
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
            </svg>
            <div class="mb-5">
                ¡Ya te encuentras registrado!
            </div>
            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en ponerte en contacto con nosotros. ¡Estamos aquí para ayudarte!</p>
            <h1 class="align-items-center mb-3">
                <span>¡Te deseamos un excelente día!</span>
            </h1>
        </div>
    </main>

<?php include_once "footer.php"; ?>