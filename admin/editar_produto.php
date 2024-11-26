<?php
session_start();
include '../config/conexao.php';


// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}


// Verifica se o ID do produto foi passado via URL
if (!isset($_GET['id'])) {
  echo "<p>ID do produto não fornecido.</p>";
  include '../includes/footer.php';
  exit;
}


$produto_id = $_GET['id'];


// Obtém os dados do produto existente para exibir no formulário
$sql_produto = "SELECT * FROM produtos WHERE id = ?";
$stmt_produto = $conn->prepare($sql_produto);
$stmt_produto->bind_param("i", $produto_id);
$stmt_produto->execute();
$result_produto = $stmt_produto->get_result();


if ($result_produto->num_rows == 0) {
  echo "<p>Produto não encontrado.</p>";
  include '../includes/footer.php';
  exit;
}


$produto = $result_produto->fetch_assoc();


// Lógica para processar a edição do produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $preco = $_POST['preco'];
  $status = $_POST['status']; // Captura o status do produto


  // Verifica se o administrador enviou uma nova imagem
  if (!empty($_FILES['imagem']['name'])) {
    $imagem = $_FILES['imagem']['name']; // Nome do arquivo da nova imagem
    $imagem_temp = $_FILES['imagem']['tmp_name']; // Caminho temporário da nova imagem
    $imagem_destino = "../imgs/produtos/" . $imagem;


    // Faz o upload da nova imagem
    if (move_uploaded_file($imagem_temp, $imagem_destino)) {
      // Atualiza o produto com a nova imagem e status
      $sql_update = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ?, status = ? WHERE id = ?";
      $stmt_update = $conn->prepare($sql_update);
      $stmt_update->bind_param("ssdssi", $nome, $descricao, $preco, $imagem, $status, $produto_id);
    } else {
      $erro = "Erro ao fazer o upload da nova imagem.";
    }
  } else {
    // Atualiza o produto sem alterar a imagem, mas com o status
    $sql_update = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssdsi", $nome, $descricao, $preco, $status, $produto_id);
  }


  // Executa a atualização no banco de dados
  if ($stmt_update->execute()) {
    $msg = "Produto atualizado com sucesso!";
  } else {
    $erro = "Erro ao atualizar o produto.";
  }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Editar Produto - Fast Food</title>
  <link rel="stylesheet" href="../css/editar-produto.css">
  <link rel="stylesheet" href="../css/header.css">


</head>


<body>
  <?php include '../includes/header.php'; ?>
  <div class="container">
    <div>
      <img class="imagem" src="../imgs/editarproduto2.png" alt="">
    </div>
  <div class="title">
        <h1>Editar Produto</h1>
      </div>
    <div class="tudo2">
      


      <!-- Exibe mensagens de sucesso ou erro -->
      <?php if (isset($msg)): ?>
        <p style="color: green;"><?= $msg; ?></p>
      <?php elseif (isset($erro)): ?>
        <p style="color: red;"><?= $erro; ?></p>
      <?php endif; ?>


      <!-- Formulário de edição de produto -->
      <form action="editar_produto.php?id=<?= $produto['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="nome">Nome do Produto:</label>
          <input type="text" name="nome" id="nome" value="<?= $produto['nome']; ?>" required>
        </div>


        <div class="form-group">
          <label for="descricao">Descrição:</label>
          <textarea name="descricao" id="descricao" required><?= $produto['descricao']; ?></textarea>
        </div>


        <div class="form-group">
          <label for="preco">Preço:</label>
          <input type="number" step="0.01" name="preco" id="preco" value="<?= $produto['preco']; ?>" required>
        </div>


        <div class="form-group">
          <label for="status">Status do Produto:</label>
          <select name="status" id="status" required>
            <option value="ativo" <?= $produto['status'] == 'ativo' ? 'selected' : ''; ?>>Ativo</option>
            <option value="inativo" <?= $produto['status'] == 'inativo' ? 'selected' : ''; ?>>Inativo</option>
          </select>
        </div>


        <div class="form-group">
          <label for="imagem">Imagem do Produto (deixe vazio para manter a atual):</label>
          <input type="file" name="imagem" id="imagem" accept="image/*">
          <p>Imagem atual:</p>
          <img src="../imgs/produtos/<?= $produto['imagem']; ?>" alt="<?= $produto['nome']; ?>" style="width: 150px;">
        </div>


        <input type="submit" value="Atualizar Produto">
      </form>
    </div>
  </div>


  <?php include '../includes/footer.php'; ?>


</body>


</html>