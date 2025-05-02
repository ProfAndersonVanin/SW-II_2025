<?php
$api_url = 'http://localhost/exemplos_aula10_swii/api.php';
$mensagem = '';
$erro = '';

// Processar formulário de cadastro
if (isset($_POST['cadastrar'])) {
    $dados = json_encode(['nome' => $_POST['nome'], 'email' => $_POST['email']]);
    $contexto = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json",
            'content' => $dados
        ]
    ]);
    
    $resultado = file_get_contents($api_url, false, $contexto);
    if ($resultado) {
        $mensagem = 'Usuário cadastrado com sucesso!';
    } else {
        $erro = 'Erro ao cadastrar usuário';
    }
}

// Processar exclusão
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $contexto = stream_context_create([
        'http' => [
            'method' => 'DELETE'
        ]
    ]);
    
    $resultado = file_get_contents($api_url . '?id=' . $id, false, $contexto);
    if ($resultado) {
        $mensagem = 'Usuário excluído com sucesso!';
    } else {
        $erro = 'Erro ao excluir usuário';
    }
}

// Carregar usuários
$usuarios = [];
$resultado = file_get_contents($api_url);
if ($resultado) {
    $usuarios = json_decode($resultado, true);
} else {
    $erro = 'Erro ao carregar usuários';
}

// Buscar usuário para edição
$editando = [];
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $resultado = file_get_contents($api_url . '?id=' . $id);
    if ($resultado) {
        $editando = json_decode($resultado, true);
    }
}

// Processar atualização
if (isset($_POST['atualizar'])) {
    $id = intval($_POST['id']);
    $dados = json_encode(['nome' => $_POST['nome'], 'email' => $_POST['email']]);
    
    $contexto = stream_context_create([
        'http' => [
            'method' => 'PUT',
            'header' => "Content-Type: application/json",
            'content' => $dados
        ]
    ]);
    
    $resultado = file_get_contents($api_url . '?id=' . $id, false, $contexto);
    if ($resultado) {
        $mensagem = 'Usuário atualizado com sucesso!';
        $editando = [];
    } else {
        $erro = 'Erro ao atualizar usuário';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>Usuários</h1>
    
    <?php if ($mensagem) { ?>
        <div class="alert alert-success"><?php echo $mensagem; ?></div>
    <?php } ?>
    
    <?php if ($erro) { ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php } ?>

    <!-- Formulário de Cadastro/Edição -->
    <form method="POST" class="mb-4 border p-3">
        <h4><?php if ($editando) { echo 'Editar'; } else { echo 'Cadastrar'; } ?> Usuário</h4>
        
        <?php if ($editando) { ?>
            <input type="hidden" name="id" value="<?php echo $editando['id']; ?>">
        <?php } ?>
        
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" 
                   value="<?php if ($editando) { echo $editando['nome']; } ?>" required>
        </div>
        
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" 
                   value="<?php if ($editando) { echo $editando['email']; } ?>" required>
        </div>
        
        <?php if ($editando) { ?>
            <button name="atualizar" class="btn btn-warning">Atualizar</button>
            <a href="?" class="btn btn-secondary">Cancelar</a>
        <?php } else { ?>
            <button name="cadastrar" class="btn btn-primary">Cadastrar</button>
        <?php } ?>
    </form>

    <!-- Lista de Usuários -->
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
            <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo $usuario['nome']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td>
                    <a href="?editar=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-info">Editar</a>
                    <a href="?excluir=<?php echo $usuario['id']; ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Tem certeza?')">Excluir</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>