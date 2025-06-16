<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar formulário de edição
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (!empty($nome) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $email, $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?editado=1");
            exit;
        } else {
            echo "Erro ao atualizar usuário.";
        }
    } else {
        echo "Nome e email não podem estar vazios.";
    }
} else {
    // Exibir formulário de edição
    $id = intval($_GET['id']);
    $usuario = $conn->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();
    
    if (!$usuario) {
        die("Usuário não encontrado");
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Editar Usuário</title>
        <link rel="stylesheet" href="../../view/css/style.css">
    </head>
    <body>
        <h2>Editar Usuário</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
            
            <button type="submit">Salvar Alterações</button>
            <a href="index.php">Cancelar</a>
        </form>
    </body>
    </html>
    <?php
}
?>