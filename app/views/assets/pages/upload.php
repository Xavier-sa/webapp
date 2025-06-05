<?php
require_once '../../../data/config.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $imagem = $_FILES['foto'];

        // Configurações
        $tamanhoMaximo = 16 * 1024 * 1024; // 16MB
        $mimeTypesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $diretorioDestino = __DIR__ . '/uploads/';

        // Validações
        if ($imagem['size'] > $tamanhoMaximo) {
            $erro = 'Arquivo muito grande (máx. 16MB)';
        } else {
            // Verifica tipo MIME e extensão
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($imagem['tmp_name']);
            
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
            
            if (!in_array($mimeType, $mimeTypesPermitidas) || 
                !in_array($extensao, $extensoesPermitidas)) {
                $erro = 'Tipo de arquivo inválido! Apenas imagens JPG, PNG ou GIF são permitidas.';
            } else {
                // Cria diretório se não existir
                if (!is_dir($diretorioDestino)) {
                    mkdir($diretorioDestino, 0755, true);
                }

                // Gera nome único para o arquivo
                $nomeUnico = uniqid() . '_' . basename($imagem['name']);
                $caminhoImagem = $diretorioDestino . $nomeUnico;

                if (move_uploaded_file($imagem['tmp_name'], $caminhoImagem)) {
                    // Insere no banco de dados
                    $sql = "INSERT INTO imagens (nome_arquivo, caminho, tipo_mime, tamanho) 
                            VALUES (:nome_arquivo, :caminho, :tipo_mime, :tamanho)";
                    $stmt = $pdo->prepare($sql);
                    if ($stmt->execute([
                        ':nome_arquivo' => $imagem['name'],
                        ':caminho' => $caminhoImagem,
                        ':tipo_mime' => $mimeType,
                        ':tamanho' => $imagem['size']
                    ])) {
                        $mensagem = 'Upload realizado com sucesso!';
                    } else {
                        $erro = 'Erro ao salvar no banco de dados.';
                        // Remove o arquivo se falhar no BD
                        unlink($caminhoImagem);
                    }
                } else {
                    $erro = 'Erro ao mover o arquivo.';
                }
            }
        }
    } else {
        $erro = 'Nenhum arquivo enviado ou erro no upload.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem-sucesso">
            <?= $mensagem ?>
            <a href="../../../../index.php">Voltar</a>
            <a href="./galeria.php">Ver Galeria</a>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($erro)): ?>
        <div class="mensagem-erro">
            <?= $erro ?>
            <a href="../../../../index.php">Voltar e tentar novamente</a>
        </div>
    <?php endif; ?>
</body>
</html>