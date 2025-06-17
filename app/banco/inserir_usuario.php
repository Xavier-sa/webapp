<?php
require 'db.php';

// Configurações
$uploadDir = 'uploads/perfis/';
$maxSize = 2 * 1024 * 1024; // 2MB
$allowedTypes = ['image/jpeg', 'image/png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $imagem_perfil_id = null; // Inicializa como null

    // Processar upload da foto de perfil se existir
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        // Verificar tipo e tamanho
        $fileType = $_FILES['foto_perfil']['type'];
        $fileSize = $_FILES['foto_perfil']['size'];
        
        if (!in_array($fileType, $allowedTypes)) {
            die("Tipo de arquivo não permitido. Use apenas JPEG ou PNG.");
        }
        
        if ($fileSize > $maxSize) {
            die("Arquivo muito grande. Máximo 2MB.");
        }

        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Processar upload
        $nomeOriginal = basename($_FILES['foto_perfil']['name']);
        $ext = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $nomeSeguro = 'perfil_' . uniqid() . '.' . $ext;
        $caminho = $uploadDir . $nomeSeguro;

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho)) {
            // Salvar na tabela imagens
            $stmt = $conn->prepare("INSERT INTO imagens (nome_original, nome_arquivo, caminho, tipo) VALUES (?, ?, ?, 'perfil')");
            $stmt->bind_param("sss", $nomeOriginal, $nomeSeguro, $caminho);
            
            if ($stmt->execute()) {
                $imagem_perfil_id = $conn->insert_id;
            } else {
                die("Erro ao salvar imagem no banco de dados.");
            }
        } else {
            die("Erro ao mover o arquivo para o servidor.");
        }
    }

    // Inserir usuário no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, imagem_perfil_id) VALUES (?, ?)");
    $stmt->bind_param("si", $nome, $imagem_perfil_id);
    
    if ($stmt->execute()) {
        header("Location: index.php?sucesso=Usuário cadastrado com sucesso");
        exit;
    } else {
        die("Erro ao cadastrar usuário no banco de dados.");
    }
}
?>