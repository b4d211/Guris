<?php 
session_start();
include 'config/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  
  // Verifica se a senha está definida antes de criar o hash
  if (isset($_POST['senha']) && !empty($_POST['senha'])) {
      $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
  } else {
      die("Senha não definida."); // Lide com a situação onde a senha não está definida
  }
  
  $bairro = $_POST['bairro'];
  $logradouro = $_POST['logradouro'];
  $numero = $_POST['numero'];
  $complemento = $_POST['complemento'];

  // Insere os dados no banco de dados
  $sql = "INSERT INTO usuarios (nome, email, senha, bairro, logradouro, numero, complemento) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssss", $nome, $email, $senha, $bairro, $logradouro, $numero, $complemento);

  if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
  } else {
    echo "Erro ao cadastrar o usuário.";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cadastro de usuario - Fast Food</title>
  <link rel="stylesheet" href="./css/cadastro.css">
  <link rel="stylesheet" href="./css/header.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
<?php include './includes/header.php'; ?>

  <div class="container">
    <div class="formulario">
      <div class="titulo">
        <img src="../imgs/GURIS.png" alt="">
      </div>
      <div class="conta">
        <h2>Criar uma Conta</h2>
      </div>

      <?php if (isset($erro)): ?>
        <p style="color: red;"><?= $erro; ?></p>
      <?php endif; ?>

      <form class="informacoes" action="cadastro.php" method="POST">
        <div class="campos">
          <div class="campo">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>
          </div>
          <div class="campo">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
          </div>
          <div class="campo">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" required>
          </div>
          <div class="campo">
            <label for="logradouro">Rua/avenida:</label>
            <input type="text" name="logradouro" id="logradouro" required>
          </div>
          <div class="campo">
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" required>
          </div>
          <div class="campo">
            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" id="complemento">
          </div>
        </div>
        <div class="camposenha">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha">
          </div>
       
        <button class="criar" type="submit">Criar</button>
      </form>
      <p class="p">Já tem uma conta? <a class="login" href="login.php">Faça login aqui</a>.</p>
    </div>
    <a class="voltar" href="/">Voltar a tela inicial</a>
  </div>

</body>

</html>
