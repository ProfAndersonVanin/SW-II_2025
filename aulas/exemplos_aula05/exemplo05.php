<?php
    //cria o array associativo
    $idades = array("Jason"=>38, "Ada"=>35, "Delphino"=>26);

    //converte o conteúdo do array associativo para uma string JSON
    $json_str = json_encode($idades);

    //imprime a string JSON
    echo "$json_str";
?>