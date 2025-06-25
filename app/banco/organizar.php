<?php

require_once __DIR__ . '/BaseModel.php';

class UsuariosModel extends BaseModel {

    public function __construct() {
        $this->tabela = 'usuarios';
        parent::__construct();
    }

    /**
     * Summary of salvar
     * @param array $usuario
     *      [ 'nome', 'email', 'imagem_perfil_id' ]
     * @return bool
     */
    public function salvar($usuario): bool {
        $query = "INSERT INTO $this->tabela (nome, email, imagem_perfil_id)
            VALUES (:nome, :email, :imagem_perfil_id)";

        $stmt = $this->pdo->prepare($query);
        
        return $stmt->execute([
          ':nome' => $usuario['nome'],
          ':email' => $usuario['email'],
          ':imagem_perfil_id' => $usuario['imagem_perfil_id']
        ]);
    }

    /**
     * Summary of buscarTodas
     * @return array
     *      [ 'id', 'nome', 'nome_original', 'data_envio', 'caminho' ]
     */
    public function buscarTodas(): array {
        $query = "SELECT * FROM $this->tabela";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}



chat codigo acima professor o meu é o abaixo :  inserir_usuario.php:<?php
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
?>  excluir_usuario.php:
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
