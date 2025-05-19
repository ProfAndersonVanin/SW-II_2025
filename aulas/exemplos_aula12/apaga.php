<?php
    include_once 'conecta.php';

    $resultado = $conexao->prepare("DELETE FROM produtos 
                                    WHERE id = :id ");

    // $id = 2;

    // $resultado->bindValue(":id",$id);
    // $resultado->execute();

    $conexao->query("DELETE FROM produtos WHERE id = 3 ");

?>