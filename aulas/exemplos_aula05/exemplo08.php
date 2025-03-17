<?php
    $dados = [
        "nome" => "João",
        "idade" => 25,
        "email" => "joao@email.com"
    ];
    
    $json = json_encode($dados, JSON_PRETTY_PRINT);
    echo $json;
?>