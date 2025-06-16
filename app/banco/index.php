<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Galeria de Imagens</title>
    <!-- <link rel="stylesheet" href="../view/css/style.css"> -->
</head>
<body>

<header>
    <h1>Galeria de Imagens</h1>

    <form action="pesquisa.php" method="get">
        <input type="text" name="q" placeholder="Pesquisar..." value="<?= $_GET['q'] ?? '' ?>">
        <button>Buscar</button>
    </form>
</header>

<main>
    <section>
        <h2>Enviar Nova Imagem</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="usuario_id">Usuário:</label>
            <select name="usuario_id" id="usuario_id" required>
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
    </section>

    <section>
        <h2>Cadastrar Usuário</h2>
        <form action="inserir_usuario.php" method="post">
            <label for="nome">Nome do usuário:</label>
            <input type="text" name="nome" id="nome" required>
            <button type="submit">Cadastrar</button>
        </form>
    </section>

    <section>
        <h2>Imagens</h2>
        <div style="display: flex; flex-wrap: wrap;">
            <?php
            $busca = $_GET['q'] ?? '';
            $sql = "SELECT * FROM imagens";

            if ($busca) {
                $sql .= " WHERE nome_original LIKE '%$busca%'";
            }

            $res = $conn->query($sql);
            while ($img = $res->fetch_assoc()):
            ?>
                <div class="imagem-card">
                    <img src="<?= $img['caminho'] ?>" width="150" alt="Imagem">
                    <p><?= $img['nome_original'] ?></p>
                    <a href="delete.php?id=<?= $img['id'] ?>" onclick="return confirm('Excluir imagem?')">Excluir</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section>
        <h2>Usuários e suas Imagens</h2>
        <?php
        $usuarios = $conn->query("SELECT * FROM usuarios");
        while ($user = $usuarios->fetch_assoc()):
        ?>
            <article class="usuario-card">
                <header>
                    <h3><?= $user['nome'] ?>
                        <a href="excluir_usuario.php?id=<?= $user['id'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?')"
                           style="color: red; margin-left: 10px;">[Excluir]</a>
                    </h3>
                </header>
                <div class="usuario-imagens">
                    <?php
                    $sql = "SELECT i.* FROM imagens i
                            JOIN imagem_usuario iu ON i.id = iu.imagem_id
                            WHERE iu.usuario_id = {$user['id']}";
                    $res = $conn->query($sql);
                    while ($img = $res->fetch_assoc()):
                    ?>
                        <div class="imagem-card">
                            <img src="<?= $img['caminho'] ?>" width="150" alt="Imagem do usuário">
                            <p><?= $img['nome_original'] ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
</main>

</body>
</html>
