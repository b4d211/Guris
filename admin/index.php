<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}

// Deletar produto
if (isset($_GET['remover_id'])) {
  $remover_id = $_GET['remover_id'];

  // 1. Deletar os itens no carrinho relacionados a este produto
  $delete_carrinho_sql = "DELETE FROM carrinho WHERE produto_id = ?";
  $stmt_carrinho = $conn->prepare($delete_carrinho_sql);
  $stmt_carrinho->bind_param("i", $remover_id);

  // 2. Deletar os itens no pedido relacionados a este produto
  $delete_itens_pedido_sql = "DELETE FROM itens_pedido WHERE produto_id = ?";
  $stmt_itens_pedido = $conn->prepare($delete_itens_pedido_sql);
  $stmt_itens_pedido->bind_param("i", $remover_id);

  // Verifica se a exclusão do carrinho foi bem-sucedida
  if ($stmt_carrinho->execute() && $stmt_itens_pedido->execute()) {
    // 3. Deletar o produto da tabela de produtos
    $delete_produto_sql = "DELETE FROM produtos WHERE id = ?";
    $stmt_produto = $conn->prepare($delete_produto_sql);
    $stmt_produto->bind_param("i", $remover_id);

    if ($stmt_produto->execute()) {
      echo "<script>alert('Produto removido com sucesso!'); window.location.href = 'index.php';</script>";
    } else {
      echo "<script>alert('Erro ao remover o produto!');</script>";
    }

    $stmt_produto->close();
  } else {
    echo "<script>alert('Erro ao remover o produto de carrinho ou pedido!');</script>";
  }

  $stmt_carrinho->close();
  $stmt_itens_pedido->close();
}

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Painel Admin - Fast Food</title>
  <link rel="stylesheet" href="../css/indexAdmin.css">
  <link rel="stylesheet" href="../css/header.css">
</head>

<body>
<?php
  $paginaAtual = 'index';
  include '../includes/header.php'; ?>
  <div class="container">
    <div>
      <img class="imagem" src="../imgs/produtos.png" alt="">
    </div>
    <div class="title">
      <h1>Produtos Cadastrados</h1>
    </div>

    <div class="produtos">
      <?php while ($produto = $result->fetch_assoc()) : ?>
        <div class="produto">
          <img src="../imgs/produtos/<?= $produto['imagem']; ?>" alt="<?= $produto['nome']; ?>">
          <h3><?= $produto['nome']; ?></h3>
          <p>Descrição: <?= $produto['descricao']; ?></p>
          <p>Preço: R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></p>
          <p>Status: <?= ucfirst($produto['status']); ?></p>

          <!-- Botões de edição e remoção para admin -->
          <a href="editar_produto.php?id=<?= $produto['id']; ?>">Editar</a>
          <a href="index.php?remover_id=<?= $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</a>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
  <?php include '../includes/footer.php'; ?>

</body>

</html>
