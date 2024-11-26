<?php
session_start();
include 'config/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Consulta para verificar o usuário
  $sql = "SELECT * FROM usuarios WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Verifica se o usuário foi encontrado
  if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    // Verifica a senha
    if (password_verify($senha, $usuario['senha'])) {
      // Define as variáveis de sessão
      $_SESSION['usuario_id'] = $usuario['id'];
      $_SESSION['role'] = $usuario['role'];

      header('Location: /index.php');
      exit;
    } else {
      $erro = "Senha incorreta!";
    }
  } else {
    $erro = "Usuário não encontrado!";
  }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login - Fast Food</title>
  <link rel="stylesheet" href="./css/login.css">
  <link rel="stylesheet" href="./css/header.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>

<body>
  <?php include 'includes/header.php'; ?>
  <div class="container">
    <img src="imgs/GURIS.png" alt="">
    <h2>login</h2>
    <?php if (isset($erro)): ?>
      <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>

    <form class="formulario" action="login.php" method="POST">
      <label for="email">Email:</label>
      <input type="email" name="email" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" required>

      <input class="botaoEnv" type="submit" value="Entrar">
    </form>

    <a href="cadastro.php">Não tem uma conta?Cadastre-se aqui</a>
  </div>

  <?php include 'includes/footer.php'; ?>

</body>

</html>