<?php
require 'db.php';

$uploadDir = 'uploads/';
$maxSize = 16 * 1024 * 1024; // 16MB
$allowedTypes = ['image/jpeg', 'image/png'];
$extensoesPermitidas = ['jpg', 'jpeg', 'png'];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // Cria o diretório, se não existir
}
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($_FILES['imagem']['type'], $allowedTypes)) {
    die("Tipo de arquivo não permitido");
}

$maxSize = 16 * 1024 * 1024; // 16MB
if ($_FILES['imagem']['size'] > $maxSize) {
    die("Arquivo muito grande. Máximo 16MB");
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $usuarioId = $_POST['usuario_id'];
    $nomeOriginal = basename($_FILES['imagem']['name']);
    $ext = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $nomeSeguro = uniqid('img_', true) . '.' . $ext;
    $caminho = $uploadDir . $nomeSeguro;

    $maxSize = 16 * 1024 * 1024; // 16MB
    if ($_FILES['imagem']['size'] > $maxSize) {
        die("Arquivo muito grande. Máximo 16MB");
    }

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        // Salva na tabela imagens
        $stmt = $conn->prepare("INSERT INTO imagens (nome_original, nome_arquivo, caminho) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nomeOriginal, $nomeSeguro, $caminho);
        $stmt->execute();
        $imagemId = $conn->insert_id;

        // Faz o vínculo com o usuário
        $stmt2 = $conn->prepare("INSERT INTO imagem_usuario (usuario_id, imagem_id) VALUES (?, ?)");
        $stmt2->bind_param("ii", $usuarioId, $imagemId);
        $stmt2->execute();

        header("Location: index.php?sucesso=1");
        exit;
    } else {
        echo "Erro ao mover o arquivo.";
    }
} else {
    echo "Erro no upload.";
}
