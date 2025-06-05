<?php
class UploadHelper {
    const MAX_SIZE = 16777216; // 16MB
    
    public function processUpload($file, $uploadDir) {
        // Verificar erros
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Erro no upload'];
        }

        // Validar tamanho
        if ($file['size'] > self::MAX_SIZE) {
            return ['success' => false, 'error' => 'Arquivo muito grande'];
        }

        // Validar tipo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
            return ['success' => false, 'error' => 'Tipo não suportado'];
        }

        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Gerar nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $uploadDir . $filename;

        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $destination,
                'mime' => $mime,
                'size' => $file['size']
            ];
        }

        return ['success' => false, 'error' => 'Falha ao mover arquivo'];
    }
}