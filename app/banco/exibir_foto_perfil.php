<?php
require 'db.php';

// Configurações
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$uploadDir = 'uploads/perfis/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação do nome
    $nome = trim($_POST['nome']);
    if (empty($nome)) {
        header("Location: index.php?erro=Nome é obrigatório");
        exit;
    }

    $imagem_perfil_id = null;
    $erros = [];

    // Processamento da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Verifica erros de upload
        if ($_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
            $erros[] = "Erro no upload da imagem: " . obter_mensagem_erro_upload($_FILES['foto_perfil']['error']);
        } else {
            // Validações da imagem
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $_FILES['foto_perfil']['tmp_name']);
            finfo_close($fileInfo);

            if (!in_array($mimeType, $allowedTypes)) {
                $erros[] = "Tipo de arquivo não permitido. Use apenas JPEG, PNG ou GIF.";
            }

            if ($_FILES['foto_perfil']['size'] > $maxFileSize) {
                $erros[] = "Arquivo muito grande. Tamanho máximo: 2MB.";
            }

            // Se não houver erros, processa o upload
            if (empty($erros)) {
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $nomeOriginal = basename($_FILES['foto_perfil']['name']);
                $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
                $nomeSeguro = 'perfil_' . uniqid() . '.' . $ext;
                $caminho = $uploadDir . $nomeSeguro;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho)) {
                    // Insere a imagem no banco
                    $stmt = $conn->prepare("INSERT INTO imagens (nome_original, nome_arquivo, caminho, tipo) VALUES (?, ?, ?, 'perfil')");
                    $stmt->bind_param("sss", $nomeOriginal, $nomeSeguro, $caminho);
                    
                    if ($stmt->execute()) {
                        $imagem_perfil_id = $conn->insert_id;
                    } else {
                        $erros[] = "Erro ao salvar imagem no banco de dados.";
                    }
                } else {
                    $erros[] = "Erro ao mover arquivo para o servidor.";
                }
            }
        }
    }

    // Se houver erros, redireciona com mensagens
    if (!empty($erros)) {
        header("Location: index.php?erro=" . urlencode(implode("<br>", $erros)));
        exit;
    }

    // Insere o usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, imagem_perfil_id) VALUES (?, ?)");
    $stmt->bind_param("si", $nome, $imagem_perfil_id);
    
    if ($stmt->execute()) {
        header("Location: index.php?sucesso=Usuário cadastrado com sucesso");
        exit;
    } else {
        header("Location: index.php?erro=Erro ao cadastrar usuário no banco de dados");
        exit;
    }
}

// Função auxiliar para mensagens de erro de upload
function obter_mensagem_erro_upload($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Arquivo muito grande';
        case UPLOAD_ERR_PARTIAL:
            return 'Upload parcialmente concluído';
        case UPLOAD_ERR_NO_FILE:
            return 'Nenhum arquivo enviado';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Pasta temporária não encontrada';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Falha ao gravar arquivo';
        case UPLOAD_ERR_EXTENSION:
            return 'Upload interrompido por extensão';
        default:
            return 'Erro desconhecido';
    }
}
?>