<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Imagens</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --background-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #e9ecef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .upload-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .upload-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--secondary-color);
        }
        
        .search-bar {
            margin-bottom: 20px;
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .image-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .image-card:hover {
            transform: translateY(-5px);
        }
        
        .image-container {
            height: 200px;
            overflow: hidden;
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .image-card:hover .image-container img {
            transform: scale(1.05);
        }
        
        .image-info {
            padding: 15px;
        }
        
        .image-title {
            font-weight: 600;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .image-meta {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .actions a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }
        
        .actions a:hover {
            text-decoration: underline;
        }
        
        .user-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }
        
        .user-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        
        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Galeria de Imagens</h1>
            <p>Compartilhe e visualize suas imagens</p>
        </header>
        
        <section class="upload-section">
            <h2>Enviar Nova Imagem</h2>
            <form class="upload-form" action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <select id="usuario" name="usuario_id" required>
                        <option value="">Selecione...</option>
                        <option value="1">Wellington</option>
                        <option value="2">Xavier</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="imagem">Selecione uma imagem</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" required>
                </div>
                
                <button type="submit">Enviar Imagem</button>
            </form>
        </section>
        
        <section class="search-bar">
            <form action="" method="GET">
                <div class="form-group">
                    <input type="text" name="q" placeholder="Pesquisar por nome da imagem..." value="">
                </div>
            </form>
        </section>
        
        <div class="gallery">
    <?php
    $res = $conn->query("SELECT i.*, u.nome as usuario_nome 
                        FROM imagens i
                        JOIN imagem_usuario iu ON i.id = iu.imagem_id
                        JOIN usuarios u ON iu.usuario_id = u.id
                        ORDER BY i.data_upload DESC");
    
    while ($img = $res->fetch_assoc()):
    ?>
    <div class="image-card">
        <div class="image-container">
            <img src="<?= htmlspecialchars($img['caminho']) ?>" alt="<?= htmlspecialchars($img['nome_original']) ?>">
        </div>
        <div class="image-info">
            <div class="image-title"><?= htmlspecialchars($img['nome_original']) ?></div>
            <div class="image-meta">
                <span><?= date('d/m/Y', strtotime($img['data_upload'])) ?></span>
                <span>Por: <?= htmlspecialchars($img['usuario_nome']) ?></span>
            </div>
            <div class="actions">
                <a href="<?= htmlspecialchars($img['caminho']) ?>" download>Download</a>
                <a href="delete.php?id=<?= $img['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>




        <section class="gallery-section">
            <h2>Todas as Imagens</h2>
            <div class="gallery">
                <!-- Exemplo de card de imagem - isso seria gerado dinamicamente pelo PHP -->
                <div class="image-card">
                    <div class="image-container">
                        <img src="uploads/img_684f43f3e89e98.69024871.PNG" alt="Imagem de exemplo">
                    </div>
                    <div class="image-info">
                        <div class="image-title">Imagem de exemplo</div>
                        <div class="image-meta">
                            <span>10/05/2023</span>
                            <span>Por: Wellington</span>
                        </div>
                        <div class="actions">
                            <a href="uploads/img_684f43f3e89e98.69024871.PNG" download>Download</a>
                            <a href="delete.php?id=1" onclick="return confirm('Tem certeza que deseja excluir esta imagem?')">Excluir</a>
                        </div>
                    </div>
                </div>
                
                <!-- Mais cards seriam gerados aqui pelo PHP -->
            </div>
        </section>
        
        <!-- Seção de imagens por usuário -->
        <section class="user-section">
            <div class="user-header">
                <img src="https://via.placeholder.com/40" alt="Avatar" class="user-avatar">
                <h2>Imagens de Wellington</h2>
            </div>
            <div class="gallery">
                <!-- Imagens do usuário específico -->
            </div>
        </section>
    </div>
</body>
</html>