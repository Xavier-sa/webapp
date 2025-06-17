<?php

require_once __DIR__ . '/app/model/ImagensModel.php';
require_once __DIR__ . '/app/service/ImagensUploadService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['foto'])) {
        $imagem = $_FILES['foto'];

        $imagemSalva = ImagensUploadService::upload($imagem);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <?php if ($imagemSalva): ?>
        <p>
            Imagem salva com sucesso em <?= $imagemSalva['caminho'] ?>.
            <a href="index.php">Voltar</a>
        </p>
    <?php endif ?>
</body>
</html>