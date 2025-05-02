<?php
// Configurações
$api_url = 'http://localhost/exemplos_aula10_swii/api.php';
$mensagem = '';
$erro = '';

// Função para fazer requisições à API
function fazerRequisicao($url, $metodo = 'GET', $dados = []) {
    $opcoes = [
        'http' => [
            'method' => $metodo,
            'header' => "Content-type: application/json\r\n",
            'ignore_errors' => true
        ]
    ];

    if ($metodo === 'POST' || $metodo === 'PUT') {
        $opcoes['http']['content'] = json_encode($dados);
    }

    if ($metodo === 'GET' && !empty($dados)) {
        $url .= '?' . http_build_query($dados);
    }

    $contexto = stream_context_create($opcoes);
    $resposta = @file_get_contents($url, false, $contexto);
    
    // Extrair código de status
    $status = 500;
    if (isset($http_response_header[0])) {
        preg_match('/HTTP\/\d\.\d\s(\d{3})/', $http_response_header[0], $matches);
        $status = $matches[1] ?? 500;
    }

    return [
        'status' => (int)$status,
        'dados' => $resposta ? json_decode($resposta, true) : null
    ];
}

// Processar operações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CADASTRAR USUÁRIO
    if (isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
        $dados = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email']
        ];
        
        $resultado = fazerRequisicao($api_url, 'POST', $dados);
        
        if ($resultado['status'] === 200) {
            $mensagem = 'Usuário cadastrado com sucesso!';
        } else {
            $erro = $resultado['dados']['erro'] ?? 'Erro ao cadastrar';
        }
    }
    
    // ATUALIZAR USUÁRIO
    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
        $dados = [
            'nome' => $_POST['editNome'],
            'email' => $_POST['editEmail']
        ];
        
        $resultado = fazerRequisicao($api_url . '?id=' . $_POST['id'], 'PUT', $dados);
        
        if ($resultado['status'] === 200) {
            $mensagem = 'Usuário atualizado com sucesso!';
        } else {
            $erro = $resultado['dados']['erro'] ?? 'Erro ao atualizar';
        }
    }
}

// PROCESSAR EXCLUSÃO
if (isset($_GET['excluir'])) {
    $resultado = fazerRequisicao($api_url . '?id=' . $_GET['excluir'], 'DELETE');
    
    if ($resultado['status'] === 200) {
        $mensagem = 'Usuário excluído com sucesso!';
    } else {
        $erro = $resultado['dados']['erro'] ?? 'Erro ao excluir';
    }
}

// BUSCAR USUÁRIO PARA EDIÇÃO
$usuario_edicao = null;
if (isset($_GET['editar'])) {
    $resultado = fazerRequisicao($api_url . '?id=' . $_GET['editar']);
    
    if ($resultado['status'] === 200) {
        $usuario_edicao = $resultado['dados'];
    } else {
        $erro = $resultado['dados']['erro'] ?? 'Usuário não encontrado';
    }
}

// LISTAR TODOS OS USUÁRIOS
$resultado = fazerRequisicao($api_url);
$usuarios = $resultado['status'] === 200 ? $resultado['dados'] : [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Usuários (PHP)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Gerenciamento de Usuários</h1>

    <?php if ($mensagem): ?>
        <div class="alert alert-success"><?= $mensagem ?></div>
    <?php endif; ?>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <!-- Seção de Cadastro -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4>Cadastrar Novo Usuário</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="acao" value="cadastrar">
                <div class="mb-3">
                    <label class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-success">Cadastrar</button>
            </form>
        </div>
    </div>

    <!-- Seção de Edição -->
    <?php if ($usuario_edicao): ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h4>Editar Usuário ID <?= $usuario_edicao['id'] ?></h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="acao" value="atualizar">
                <input type="hidden" name="id" value="<?= $usuario_edicao['id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="editNome" 
                           value="<?= $usuario_edicao['nome'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="editEmail" 
                           value="<?= $usuario_edicao['email'] ?>" required>
                </div>
                <button type="submit" class="btn btn-warning">Atualizar</button>
                <a href="?excluir=<?= $usuario_edicao['id'] ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Tem certeza?')">Excluir</a>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Listagem de Usuários -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h4>Lista de Usuários</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= $usuario['nome'] ?></td>
                        <td><?= $usuario['email'] ?></td>
                        <td>
                            <a href="?editar=<?= $usuario['id'] ?>" class="btn btn-sm btn-info">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>