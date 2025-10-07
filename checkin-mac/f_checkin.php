<?php
    function validarCedula($cedula) {
        // Verificar que la cédula tenga 10 dígitos
        if (strlen($cedula) != 10 || !is_numeric($cedula)) {
            return false;
        }

        // Dividir la cédula en las 9 primeras cifras y el dígito verificador
        $cedulaNumeros = str_split($cedula);
        $numeros = array_slice($cedulaNumeros, 0, 9); // primeros 9 números
        $digitoVerificador = (int)$cedulaNumeros[9]; // el dígito verificador

        // Variables para las sumas
        $suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $digito = (int)$numeros[$i];
            
            // Si la posición es impar (0, 2, 4, 6, 8), multiplicar por 2
            if ($i % 2 == 0) {
                $digito *= 2;
                // Si el resultado de la multiplicación es mayor a 9, restar 9
                if ($digito > 9) {
                    $digito -= 9;
                }
            }
            
            // Sumar el dígito al total
            $suma += $digito;
        }

        // Calcular el dígito verificador esperado
        $mod10 = $suma % 10;
        $digitoEsperado = ($mod10 == 0) ? 0 : 10 - $mod10;

        // Comparar el dígito verificador con el que está en la cédula
        return $digitoVerificador == $digitoEsperado;
    }
?>