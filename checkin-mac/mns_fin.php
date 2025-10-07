<?php
session_destroy();
session_unset();
$m = 1;
include_once "header.php";
?>

<main>
    <br>
    <div class="container" align='center'>
        <div style="max-width: 400px; width: 100%; margin: 0 auto;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: auto;" fill="currentColor" class="bi bi-file-earmark-check-fill" viewBox="0 0 16 16">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m1.354 4.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708" />
            </svg>
        </div>
        <div class="mb-5">
            <br>
            <h4>Registro exitoso</h4>
        </div>
        <h1 class="align-items-center mb-3">
            <span>¡Gracias por confiar en nosotros!</span>
        </h1>
        <p>Estamos felices de tenerte cerca.
            Hemos enviado un correo
            electónico a tu bandeja de entrada.
            Si no lo ves, por favor revisa tu
            carpeta de spam o correo no
            deseado.</p>
        <div class="footer">
            <!--<p><a href="mailto:contacto@empresa.com">contacto@empresa.com</a></p>-->
        </div>
    </div>
</main>

<?php include_once "footer.php"; ?>