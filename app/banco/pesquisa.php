<?php
require 'db.php';

$q = $_GET['q'] ?? '';

if ($q) {
    $q = $conn->real_escape_string($q);
    $sql = "
        SELECT i.*, u.nome AS nome_usuario 
        FROM imagens i
        JOIN imagem_usuario iu ON i.id = iu.imagem_id
        JOIN usuarios u ON iu.usuario_id = u.id
        WHERE i.nome_original LIKE '%$q%'
        ORDER BY i.id DESC
    ";
} else {
    $sql = "
        SELECT i.*, u.nome AS nome_usuario 
        FROM imagens i
        JOIN imagem_usuario iu ON i.id = iu.imagem_id
        JOIN usuarios u ON iu.usuario_id = u.id
        ORDER BY i.id DESC
    ";
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $imagemPath = htmlspecialchars($row['caminho']);
    $nomeOriginal = htmlspecialchars($row['nome_original']);
    $nomeUsuario = htmlspecialchars($row['nome_usuario']);

    echo "
        <div style='margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; width: fit-content;'>
            <img src='{$imagemPath}' alt='{$nomeOriginal}' style='max-width: 150px; display: block; margin-bottom: 5px;' />
            <strong>Arquivo:</strong> {$nomeOriginal}<br>
            <strong>Enviado por:</strong> {$nomeUsuario}
        </div>
    ";
}
?>
