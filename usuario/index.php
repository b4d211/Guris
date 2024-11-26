<?php
session_start();
include '../config/conexao.php';

$sql = "SELECT * FROM produtos WHERE status = 'ativo'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fast Food</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>

<?php
  $paginaAtual = 'index';
  include '../includes/header.php'; ?>

  <section id="home">
    <div class="shape"></div>
    <div class="principal">
      <div id="cta">
        <h1>O sabor vai até <span>você</span></h1>
        <p class="descripion">
          Bem-vindo à Churrascaria Guris, onde a tradição do churrasco brasileiro ganha vida em cada prato! Nossa missão é proporcionar uma experiência gastronômica inesquecível, com cortes nobres de carnes selecionadas, assadas à perfeição no fogo de chão.
        </p>

        <div id="cta_buttons">
          <a href="#" class="btn-default">Ver Cardápio</a>
          <button class="btn-default">(21) 99765-1721</button>
        </div>
      </div>

      <div id="banner">
        <img src="../imgs/prato.png" alt="">
      </div>
    </div>
  </section>

  <div class="container">
    <div class="letreiro">
      <h1>Pratos</h1>
    </div>

    <div class="produtos">
      <?php while ($produto = $result->fetch_assoc()) : ?>
        <div class="produto" style="position: relative;">
          <img src="../imgs/produtos/<?= $produto['imagem']; ?>" alt="<?= $produto['nome']; ?>">
          <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
          <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
          <p class="preco">R$<?php echo htmlspecialchars($produto['preco']); ?></p>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'cliente') : ?>
            <form action="adicionar_ao_carrinho.php" method="POST">
              <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
              <div class="botao">
                <input class="adicionar" type="submit" value="Adicionar ao Carrinho">
                <input class="quantidade" type="number" name="quantidade" value="1" min="1">
              </div>
            </form>
          <?php else : ?>
            <a href="../login.php">
              <img class="carrinho" src="../imgs/produtos/imagem" alt="" style="position: absolute; top: 10px; right: 10px; width: 30px; height: auto;">
            </a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php include "../includes/footer.php"; ?>

</body>

</html>