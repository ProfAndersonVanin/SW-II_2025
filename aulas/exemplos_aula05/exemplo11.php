<?php
    $dados = [
        "produto" => "Teclado",
        "preco" => 150,
        "estoque" => 20
    ];
    
    $json = json_encode($dados, JSON_PRETTY_PRINT);
    file_put_contents("dados2.json", $json);
?>