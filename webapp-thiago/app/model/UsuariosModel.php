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
