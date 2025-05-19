<?php
    include_once 'conecta.php';

    $resultado = $conexao->prepare("SELECT * FROM produtos");         
    $resultado->execute();

    $final = $resultado->fetchAll(PDO::FETCH_ASSOC); 
    

    foreach ($final as $produto) {
        echo "ID: " . $produto['id'] . "<br>";
        echo "Nome: " . $produto['nome'] . "<br>";
        echo "Preço: R$ " . $produto['preco'] . "<br><hr>";
    }
?>