<?php require_once 'db.php'; ?>

<form action="pesquisa.php" method="get">
    <input type="text" name="q" placeholder="Pesquisar..." value="<?= $_GET['q'] ?? '' ?>">
    <button>Buscar</button>
</form>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label>Usuário:</label>
    <select name="usuario_id" required>
        <?php
        $usuarios = $conn->query("SELECT * FROM usuarios");
        while ($u = $usuarios->fetch_assoc()) {
            echo "<option value='{$u['id']}'>{$u['nome']}</option>";
        }
        ?>
    </select><br><br>

    <input type="file" name="imagem" required>
    <button type="submit">Enviar Imagem</button>
</form>
<form action="inserir_usuario.php" method="post">
    <label>Nome do usuário:</label>
    <input type="text" name="nome" required>
    <button type="submit">Cadastrar</button>
</form>


<?php
$busca = $_GET['q'] ?? '';
$sql = "SELECT * FROM imagens";

if ($busca) {
    $sql .= " WHERE nome_original LIKE '%$busca%'";
}

$res = $conn->query($sql);

echo "<div style='display: flex; flex-wrap: wrap;'>";
while ($img = $res->fetch_assoc()) {
    echo "<div style='margin: 10px; text-align: center;'>
        <img src='{$img['caminho']}' width='150'><br>
        {$img['nome_original']}<br>
        <a href='delete.php?id={$img['id']}' onclick=\"return confirm('Excluir?')\">Excluir</a>
    </div>";
}
echo "</div>";

$usuarios = $conn->query("SELECT * FROM usuarios");

while ($user = $usuarios->fetch_assoc()) {
    echo "<h2>{$user['nome']}</h2>";

    $sql = "SELECT i.* FROM imagens i
            JOIN imagem_usuario iu ON i.id = iu.imagem_id
            WHERE iu.usuario_id = {$user['id']}";

    $res = $conn->query($sql);

    echo "<div style='display:flex; flex-wrap:wrap'>";
    while ($img = $res->fetch_assoc()) {
        echo "<div style='margin:10px; text-align:center'>
                <img src='{$img['caminho']}' width='150'><br>
                {$img['nome_original']}<br>
              </div>";
    }
    echo "</div>";
}
?>