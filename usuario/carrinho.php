<?php
session_start();
include '../config/conexao.php';


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}


// Obtém os itens do carrinho para o usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT c.quantidade, p.id as produto_id, p.nome, p.preco, p.imagem
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();


$total = 0;
$items = []; // Array para armazenar os itens do carrinho


// Armazena os itens do carrinho no array
while ($item = $result->fetch_assoc()) {
  $items[] = $item;
  $total += $item['preco'] * $item['quantidade'];
}


// Quando o formulário for enviado, processa o pedido e finaliza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $metodo_pagamento = $_POST['metodo_pagamento'];


  // Cria um novo pedido
  $sql_pedido = "INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'pendente')";
  $stmt_pedido = $conn->prepare($sql_pedido);
  $stmt_pedido->bind_param("id", $usuario_id, $total);


  if ($stmt_pedido->execute()) {
    $pedido_id = $stmt_pedido->insert_id;


    // Insere os itens do pedido usando o array de itens do carrinho
    foreach ($items as $item) {
      $sql_itens = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco)
                          VALUES (?, ?, ?, ?)";
      $stmt_itens = $conn->prepare($sql_itens);
      $stmt_itens->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
      $stmt_itens->execute();
    }


    // Limpa o carrinho do usuário
    $sql_limpar = "DELETE FROM carrinho WHERE usuario_id = ?";
    $stmt_limpar = $conn->prepare($sql_limpar);
    $stmt_limpar->bind_param("i", $usuario_id);
    $stmt_limpar->execute();


    // Redireciona para a página de compras após o pedido ser finalizado
    header('Location: compras.php');
    exit;
  } else {
    $erro = "Erro ao finalizar o pedido. Tente novamente.";
  }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Meu Carrinho - Fast Food</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/carrinho.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>


<body>
<?php
  $paginaAtual = 'carrinho';
  include '../includes/header.php'; ?>
  <div class="container">
    <img src="../imgs/carro.png" alt="">
    <h1>Carrinho</h1>
    <hr>

    <div class="carrinho">
      <?php if (count($items) > 0): ?>
        <?php foreach ($items as $item): ?>
          <div class="item-carrinho">
            <div class="imagem-produto">
            <img src="../imgs/produtos/<?= $item['imagem']; ?>" alt="<?= $item['nome']; ?>">

            </div>
            <div class="detalhes-produto">
              <h3><?= $item['nome']; ?></h3>
              <p> R$ <?= number_format($item['preco'], 2, ',', '.'); ?></p>
              <p> <?= $item['quantidade']; ?></p>
            </div>
            <div class="remover-item">
              <form action="remover-do-carrinho.php" method="POST">
                <input type="hidden" name="produto_id" value="<?= $item['produto_id']; ?>">
                <button class="remov" type="submit"><img src="../imgs/lixo.png" alt=""></button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="total-carrinho">
        <h1>Total</h1><h2> R$ <?= number_format($total, 2, ',', '.'); ?></h2>
        </div>


        <!-- Formulário para escolher o método de pagamento e finalizar o pedido -->
        <form class="pagar" action="carrinho.php" method="POST">
          <div class="escolha">
          <h3>Escolha o método de pagamento:</h3>
          <select name="metodo_pagamento" required>
            <option value="cartao_credito">Cartão de Crédito</option>
            <option value="cartao_debito">Cartão de Débito</option>
            <option value="pix">PIX</option>
          </select>
          </div>
          <div class="comfir">
          <input type="submit" value="confirmar">
          </div>



        </form>
      <?php else: ?>
        <p>Seu carrinho está vazio.</p>
      <?php endif; ?>
    </div>
  </div>


  <?php include '../includes/footer.php'; ?>
</body>


</html>