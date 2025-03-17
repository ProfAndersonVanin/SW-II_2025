<?php
    $conteudo = file_get_contents("dados.json");
    $dados = json_decode($conteudo, true);
    
    print_r($dados);
?>