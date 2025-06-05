<?php

class ImagensModel extends BaseModel
{
    private $table = 'imagens';

    // CREATE
    public function create($nomeArquivo, $caminho, $tipoMime, $tamanho)
    {
        $sql = "INSERT INTO {$this->table} (nome_arquivo, caminho, tipo_mime, tamanho) 
                VALUES (:nome_arquivo, :caminho, :tipo_mime, :tamanho)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_arquivo', $nomeArquivo);
        $stmt->bindParam(':caminho', $caminho);
        $stmt->bindParam(':tipo_mime', $tipoMime);
        $stmt->bindParam(':tamanho', $tamanho);
        return $stmt->execute();
    }

    // READ - get all
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY data_upload DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ - get by ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $nomeArquivo, $caminho, $tipoMime, $tamanho)
    {
        $sql = "UPDATE {$this->table} 
                SET nome_arquivo = :nome_arquivo, caminho = :caminho, tipo_mime = :tipo_mime, tamanho = :tamanho 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_arquivo', $nomeArquivo);
        $stmt->bindParam(':caminho', $caminho);
        $stmt->bindParam(':tipo_mime', $tipoMime);
        $stmt->bindParam(':tamanho', $tamanho);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }



    // view GALEROA
    public function getGalleryImages()
    {
        $sql = "SELECT id, caminho FROM {$this->table} ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function associateWithUser($userId, $imageId, $isPublic = true)
    {
        $sql = "INSERT INTO usuario_imagens (usuario_id, imagem_id, is_public) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $imageId, $isPublic]);
    }

    public function getUserImages($userId)
    {
        $sql = "SELECT i.* FROM imagens i 
                JOIN usuario_imagens ui ON i.id = ui.imagem_id
                WHERE ui.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicGallery()
    {
        $sql = "SELECT i.*, u.nome as usuario_nome 
                FROM imagens i
                JOIN usuario_imagens ui ON i.id = ui.imagem_id
                JOIN usuarios u ON ui.usuario_id = u.id
                WHERE ui.is_public = TRUE
                ORDER BY i.data_upload DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
