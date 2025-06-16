<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);

    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        
        if ($stmt->execute()) {
            header("Location: index.php?sucesso=1");
            exit;
        } else {
            echo "Erro ao inserir usuário.";
        }
    } else {
        echo "Nome não pode estar vazio.";
    }
}
?>
