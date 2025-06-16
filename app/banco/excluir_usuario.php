
<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança: evita SQL injection

    // Excluir relacionamento com imagens (se houver)
    $stmt = $conn->prepare("DELETE FROM imagem_usuario WHERE usuario_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Excluir o usuário
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: index.php?usuario_excluido=1");
        exit;
    } else {
        echo "Erro ao excluir usuário.";
    }
}
?>
