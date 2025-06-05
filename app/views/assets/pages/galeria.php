<?php
require_once '../../../data/config.php';

// Buscar imagens no banco (pegando id e caminho)
$sql = "SELECT id, caminho FROM imagens ORDER BY data_upload DESC";
$stmt = $pdo->query($sql);
$imagens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Galeria Imagens</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h2>Galeria Imagens</h2>
    <div class="gallery">
    <?php if (count($imagens) > 0): ?>
        <?php foreach ($imagens as $img): ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($img['caminho']); ?>" alt="Imagem">
                <div class="card-actions">
                    <a class="btn download" href="download.php?id=<?= $img['id'] ?>">Download</a>
                    <a class="btn delete" href="delete.php?id=<?= $img['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta imagem?')">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php else: ?>
            <p>Nenhuma imagem encontrada na galeria.</p>
        <?php endif; ?>
    </div>

    <p>
        <a href="../../../../index.php">Voltar</a>
    </p>
</body>

</html>



