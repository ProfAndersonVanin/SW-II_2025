<?php
    //string json
    //agora o primeiro empregado possui dependentes e os outros não.
    //também foi acrescentado um campo denominado "data", contendo a data do arquivo de empregados
    $json_str = '{"empregados": '. 
    '[{"nome":"Jason Jones", "idade":38, "sexo": "M", "dependentes": ["Sedna Jones", "Ian Jones"]},'.
    '{"nome":"Ada Pascalina", "idade":35, "sexo": "F"},'.
    '{"nome":"Delphino da Silva", "idade":26, "sexo": "M"}'.
    '],
    "data": "15/12/2012"}';

    //faz o parsing da string, criando o array "empregados"
    $jsonObj = json_decode($json_str);

    //cria o array de empregados
    $empregados = $jsonObj->empregados;

    //imprime a data do arquivo e navega pelos elementos do array, imprimindo cada empregado. 
    //caso o empregado possua dependentes, estes também são exibidos.
    echo "<b>data do arquivo</b>: $jsonObj->data<br/>";
    foreach ( $empregados as $e ){
        echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br/>";
        if (property_exists($e, "dependentes")) { 
            $deps = $e->dependentes;
            echo "dependentes: <br/>";
            foreach ( $deps as $d ) echo "- $d<br/>";
        }
    }
?>