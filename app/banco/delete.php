<?php
require 'db.php';

$id = intval($_GET['id']); // Garante que seja um número inteiro

// Busca a imagem
$res = $conn->query("SELECT * FROM imagens WHERE id = $id");

if ($res->num_rows > 0) {
    $img = $res->fetch_assoc();

    // Deleta o arquivo físico
    if (file_exists($img['caminho'])) {
        unlink($img['caminho']);
    }

    // Primeiro, apaga o vínculo com o usuário
    $conn->query("DELETE FROM imagem_usuario WHERE imagem_id = $id");

    // Depois, apaga a imagem do banco
    $conn->query("DELETE FROM imagens WHERE id = $id");
}

header("Location: index.php");
exit;
?>
