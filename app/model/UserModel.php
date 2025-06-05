<?php
class UserModel extends BaseModel {
    private $table = 'usuarios';

    public function create($nome, $email, $senha) {
        $sql = "INSERT INTO {$this->table} (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        return $stmt->execute([$nome, $email, $senhaHash]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfileImage($userId, $imageId) {
        $sql = "UPDATE {$this->table} SET imagem_perfil_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$imageId, $userId]);
    }
}