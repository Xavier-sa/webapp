<?php

class UploadController {
    private $imageModel;
    private $userModel;

    public function __construct() {
        $this->imageModel = new ImageModel();
        $this->userModel = new UserModel();
    }

    public function handleUpload() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
            // Validações (tamanho, tipo, etc.)
            $uploadDir = __DIR__ . '/../../assets/uploads/';
            
            // Processar upload
            $uploadHelper = new UploadHelper();
            $result = $uploadHelper->processUpload($_FILES['foto'], $uploadDir);
            
            if ($result['success']) {
                // Salvar no banco
                $imageId = $this->imageModel->create(
                    $result['filename'],
                    $result['path'],
                    $result['mime'],
                    $result['size']
                );
                
                // Associar com usuário
                $this->imageModel->associateWithUser(
                    $_SESSION['user_id'],
                    $imageId,
                    $_POST['is_public'] ?? true
                );
                
                header('Location: /gallery?upload=success');
            } else {
                // Tratar erros
            }
        }
    }
}

?>