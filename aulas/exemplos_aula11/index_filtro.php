<?php
// Obter o nome buscado via GET
$busca = isset($_GET['busca']) ? strtolower(trim($_GET['busca'])) : '';

// Buscar dados da API
$url = "https://restcountries.com/v3.1/all";
$response = file_get_contents($url);
$paises = json_decode($response, true);

// Filtrar os países se houver busca
if ($busca !== '') {
    $paises = array_filter($paises, function ($pais) use ($busca) {
        return strpos(strtolower($pais['name']['common']), $busca) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de Países</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <h1>Países do Mundo</h1>

  <form method="GET" class="form-busca">
    <input type="text" name="busca" placeholder="Digite o nome do país" value="<?php echo htmlspecialchars($busca); ?>">
    <button type="submit">Buscar</button>
  </form>

  <div class="container">
    <?php if (empty($paises)): ?>
      <p>Nenhum país encontrado.</p>
    <?php else: ?>
      <?php foreach ($paises as $pais): ?>
        <div class="card">
          <h3><?php echo $pais['name']['common']; ?></h3>
          <img src="<?php echo $pais['flags']['svg']; ?>" alt="Bandeira de <?php echo $pais['name']['common']; ?>">
          <p><strong>População:</strong> <?php echo number_format($pais['population'], 0, ',', '.'); ?></p>
          <p><strong>Continente:</strong> <?php echo $pais['continents'][0]; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
