<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança: evita SQL injection

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?excluido=1");
        exit;
    } else {
        echo "Erro ao excluir usuário.";
    }
} else {
    echo "ID não fornecido.";
}
?>
