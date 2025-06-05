<?php
require_once '../../../data/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido.');
}

$id = (int)$_GET['id'];

// Primeiro busca a imagem no banco
$sql = "SELECT caminho FROM imagens WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$imagem = $stmt->fetch();

if (!$imagem) {
    die('Imagem não encontrada.');
}

// Tenta deletar o arquivo físico
if (file_exists($imagem['caminho'])) {
    unlink($imagem['caminho']);
}

// Depois deleta do banco
$sql = "DELETE FROM imagens WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header('Location: galeria.php');
exit;
?>