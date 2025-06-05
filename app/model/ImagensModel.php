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
}
