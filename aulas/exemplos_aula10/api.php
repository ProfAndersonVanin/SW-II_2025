<?php
    //CABEÇALHO
    header("Content-Type: application/json; charset=UTF-8"); //DEFINE O TIPO DE RESPOSTA

    $metodo = $_SERVER['REQUEST_METHOD'];    

    // RECUPERA O ARQUIVO JSON NA MESMA PASTA DO PROJETO
    $arquivo = 'usuarios.json';

    // VERIFICA SE O ARQUIVO EXISTE, SE NÃO EXISTIR CRIA UM COM ARRAY VAZIO
    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // LÊ O CONTEÚDO DO ARQUIVO JSON
    $usuarios = json_decode(file_get_contents($arquivo), true);    

    switch ($metodo) {
        case 'GET':            
            // Verifica se há um parâmetro "id" na URL
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $usuario_encontrado = null;

                // Procura o usuário pelo ID
                foreach ($usuarios as $usuario) {
                    if ($usuario['id'] == $id) {
                        $usuario_encontrado = $usuario;
                        break;
                    }
                }

                if ($usuario_encontrado) {
                    echo json_encode($usuario_encontrado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode(["erro" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
                }
            } else {
                // Retorna todos os usuários
                echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
        case 'POST':
            $dados = json_decode(file_get_contents('php://input'), true);

            // VERIFICA CAMPOS OBRIGATÓRIOS (sem exigir o ID)
            if (!isset($dados["nome"]) || !isset($dados["email"])) {
                http_response_code(400);
                echo json_encode(["erro" => "Nome e email são obrigatórios."], JSON_UNESCAPED_UNICODE);
                exit;
            }
            // GERA UM NOVO ID ÚNICO
            $novo_id = 1;
            if (!empty($usuarios)) {
                $ids = array_column($usuarios, 'id');
                $novo_id = max($ids) + 1;
            }

            $novo_usuario = [
                "id" => $novo_id,
                "nome" => $dados["nome"],
                "email" => $dados["email"],
            ];
            // ADICIONA O NOVO USUÁRIO
            $usuarios[] = $novo_usuario;

            // SALVA NO ARQUIVO
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // RETORNA CONFIRMAÇÃO
            echo json_encode([
                "mensagem" => "Usuário inserido com sucesso!",
                "usuario" => $novo_usuario
            ], JSON_UNESCAPED_UNICODE);
            break;            
        case 'PUT':
            // Verifica se o ID foi enviado via URL
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(["erro" => "ID é obrigatório para atualização."], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $id = intval($_GET['id']);
        
            // Lê os dados do corpo da requisição
            $dados = json_decode(file_get_contents('php://input'), true);
        
            // Valida campos obrigatórios
            if (!isset($dados["nome"]) || !isset($dados["email"])) {
                http_response_code(400);
                echo json_encode(["erro" => "Nome e email são obrigatórios."], JSON_UNESCAPED_UNICODE);
                exit;
            }
        
            // Procura o usuário pelo ID
            $usuario_encontrado = null;
            foreach ($usuarios as $index => $usuario) {
                if ($usuario['id'] == $id) {
                    $usuario_encontrado = $index;
                    break;
                }
            }
        
            if ($usuario_encontrado === null) {
                http_response_code(404);
                echo json_encode(["erro" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
                exit;
            }
        
            // Atualiza os dados
            $usuarios[$usuario_encontrado]['nome'] = $dados['nome'];
            $usuarios[$usuario_encontrado]['email'] = $dados['email'];
        
            // Salva no arquivo
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
            // Retorna confirmação
            echo json_encode([
                "mensagem" => "Usuário atualizado com sucesso!",
                "usuario" => $usuarios[$usuario_encontrado]
            ], JSON_UNESCAPED_UNICODE);
            break;
        case 'DELETE':
            // Verifica se o ID foi enviado via URL
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(["erro" => "ID é obrigatório para exclusão."], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $id = intval($_GET['id']);
        
            // Procura o usuário pelo ID
            $usuario_encontrado = null;
            foreach ($usuarios as $index => $usuario) {
                if ($usuario['id'] == $id) {
                    $usuario_encontrado = $index;
                    break;
                }
            }
        
            if ($usuario_encontrado === null) {
                http_response_code(404);
                echo json_encode(["erro" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
                exit;
            }
        
            // Remove o usuário do array
            array_splice($usuarios, $usuario_encontrado, 1);
        
            // Salva no arquivo
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
            // Retorna confirmação
            echo json_encode(["mensagem" => "Usuário excluído com sucesso!"], JSON_UNESCAPED_UNICODE);
            break;
        
        default:            
            http_response_code(405); // Método não permitido
            echo json_encode(["erro" => "Método não permitido!"], JSON_UNESCAPED_UNICODE);
            break;
    }    
?>