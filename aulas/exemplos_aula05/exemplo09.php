<?php
    $json = '{"nome": "Maria", "idade": 30, "email": "maria@email.com"}';

    $dados = json_decode($json, true); // O segundo parâmetro "true" converte para array
    print_r($dados);
?>