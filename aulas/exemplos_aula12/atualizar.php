<?php
    include_once 'conecta.php';

    $resultado = $conexao->prepare("UPDATE produtos SET nome = :n
                                    WHERE id = :id ");

    // $nome = "Caneta BIC";
    // $id = 1;

    // $resultado->bindValue(":id",$id);
    // $resultado->bindValue(":n",$nome);
    // $resultado->execute();

    $conexao->query("UPDATE produtos SET preco = '2.05' WHERE id = '1' ");


?>