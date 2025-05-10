<?php
    // Obter os dados da API
    $url = "https://restcountries.com/v3.1/all";
    $response = file_get_contents($url);
    $paises = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Países</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <h1>Países do Mundo</h1>
  <div class="container">
    <?php foreach ($paises as $pais): ?>
      <div class="card">
        <h3><?php echo $pais['name']['common']; ?></h3>
        <img src="<?php echo $pais['flags']['svg']; ?>" alt="Bandeira de <?php echo $pais['name']['common']; ?>">
        <p><strong>População:</strong> <?php echo number_format($pais['population'], 0, ',', '.'); ?></p>
        <p><strong>Continente:</strong> <?php echo $pais['continents'][0]; ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>