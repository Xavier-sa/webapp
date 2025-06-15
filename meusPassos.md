1. **Faz upload de imagens** ok
2. **Salva a imagem em uma pasta específica**ok
3. **Grava os dados no banco de dados**ok
4. **Exibe uma galeria com todas as imagens**ok
5. **Permite deletar imagens**ok
6. **Inclui campo de pesquisa**ok
7. **Evita sobrescrever arquivos com nomes iguais**ok
8. **Trata erros com clareza**ok
### 🔧 Estrutura do Projeto
webapp/
├── uploads/               # pasta onde as imagens serão salvas
├── db.php                 # conexão com o banco de dados
├── upload.php             # script de upload
├── delete.php             # exclusão de imagem
├── index.php              # galeria + pesquisa
├── style.css              # estilo da galeria
└── banco.sql              # script para criar a tabela no banco
### 🧰 Tratamento de Erros

* Arquivos com nomes iguais **não sobrescrevem**: usamos `uniqid()` para gerar nomes únicos.
* Ao **deletar**, primeiro apagamos a imagem da pasta com `unlink()`.
* Se houver **erro de upload**, o código trata e informa.
