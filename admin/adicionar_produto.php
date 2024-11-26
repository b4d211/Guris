<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}

// Lógica para processar o formulário de cadastro de produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validação básica dos campos
  $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
  $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
  $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
  $imagem = isset($_FILES['imagem']['name']) ? $_FILES['imagem']['name'] : '';
  $imagem_temp = isset($_FILES['imagem']['tmp_name']) ? $_FILES['imagem']['tmp_name'] : '';

  // Verifica se todos os campos obrigatórios estão preenchidos
  if (empty($nome) || empty($descricao) || empty($preco) || empty($imagem_temp)) {
    $erro = "Todos os campos são obrigatórios.";
  } else {
    // Diretório onde as imagens serão armazenadas
    $imagem_destino = "../imgs/produtos/" . basename($imagem);

    // Verifica se a imagem tem um formato válido
    $extensao = strtolower(pathinfo($imagem, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extensao, $extensoes_permitidas)) {
      $erro = "Formato de imagem inválido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
    } else {
      // Verifica o tamanho da imagem (máximo 5MB)
      if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
        $erro = "A imagem não pode ter mais de 5MB.";
      } else {
        // Tenta mover a imagem para o diretório de destino
        if (move_uploaded_file($imagem_temp, $imagem_destino)) {
          // Insere o novo produto no banco de dados
          $sql = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ssds", $nome, $descricao, $preco, $imagem);

          if ($stmt->execute()) {
            $msg = "Produto cadastrado com sucesso!";
          } else {
            $erro = "Erro ao cadastrar o produto. Tente novamente.";
          }
        } else {
          $erro = "Erro ao fazer o upload da imagem.";
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Adicionar Produto - Fast Food</title>
  <link rel="stylesheet" href="../css/produto.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>

<body>

<?php
  $paginaAtual = 'adicionar_produto';
  include '../includes/header.php'; ?>

  <div class="container">
    <h1>Cadastrar Produto</h1>

    <?php if (isset($msg)) { ?>
      <div class="success"><?php echo $msg; ?></div>
    <?php } elseif (isset($erro)) { ?>
      <div class="error"><?php echo $erro; ?></div>
    <?php } ?>

    <form action="adicionar_produto.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nome">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" required>
      </div>

      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" required></textarea>
      </div>

      <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" id="preco" required>
      </div>

      <div class="form-group">
        <label for="imagem">Imagem do Produto:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" required>
      </div>

      <input type="submit" value="Cadastrar Produto">
    </form>
  </div>

  <?php include "../includes/footer.php"; ?>

</body>

</html>
