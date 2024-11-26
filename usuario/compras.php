<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}

// Obtém os pedidos do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY created_at DESC";  // Alterado para 'created_at'
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Meus Pedidos - Fast Food</title>
  <link rel="stylesheet" href="../css/compras.css">
  <link rel="stylesheet" href="../css/header.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>

<body>
<?php
  $paginaAtual = 'compras';
  include '..\includes/header.php'; ?>

  <div class="container">
    <img class="img" src="../imgs/carne.png" alt="">
    <div class="title">
      <h1>Meus Pedidos</h1>
    </div>

    <?php if ($result->num_rows > 0): ?>
      <div class="lista-compras">
        <?php while ($pedido = $result->fetch_assoc()): ?>
          <div class="compra-item">
            <div class="tudo">
              <img class="imgusuario" src="../imgs/usuario.png" alt="">
              <div class="compra-info">
                <h3>Pedido #<?= $pedido['id']; ?></h3>
                <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
                <div class="pendente">
                  <strong>Status:</strong>
                  <p class="status"><?= ucfirst($pedido['status']); ?></p>
                </div>


                <!-- Buscar os itens do pedido -->
                <?php
                // Consulta os itens do pedido
                $pedido_id = $pedido['id'];
                $sql_itens = "SELECT ip.*, p.nome AS produto_nome, p.preco AS produto_preco
                                              FROM itens_pedido ip
                                              INNER JOIN produtos p ON ip.produto_id = p.id
                                              WHERE ip.pedido_id = ?";
                $stmt_itens = $conn->prepare($sql_itens);
                $stmt_itens->bind_param("i", $pedido_id);
                $stmt_itens->execute();
                $result_itens = $stmt_itens->get_result();

                if ($result_itens->num_rows > 0): ?>
                  <div class="itens-pedido">
                    <h4>Itens do Pedido:</h4>
                    <ul>
                      <?php while ($item = $result_itens->fetch_assoc()): ?>
                        <li>
                          <p><?= $item['quantidade']; ?>x <?= $item['produto_nome']; ?></p>
                        </li>
                      <?php endwhile; ?>
                      <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>
                    </ul>
                  </div>
                <?php else: ?>
                  <p>Este pedido não contém itens.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Você ainda não fez nenhuma compra.</p>
    <?php endif; ?>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>

</html>