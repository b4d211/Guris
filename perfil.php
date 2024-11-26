<?php
session_start();
include './config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * from usuarios where id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bairro = $_POST['bairro'];
  $logradouro = $_POST['logradouro'];
  $numero = $_POST['numero'];
  $complemento = $_POST['complemento'];
  $usuario_id = $_SESSION['usuario_id'];

  // Atualizando os dados do usuário no banco
  $sql = "UPDATE usuarios SET bairro = ?, logradouro = ?, numero = ?, complemento = ? WHERE id = ?";
  $stmt = $conn->prepare($sql); // Preparando a consulta
  $stmt->bind_param("ssssi", $bairro, $logradouro, $numero, $complemento, $usuario_id); // Vinculando os parâmetros

  if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso!";
  } else {
    echo "Erro ao atualizar perfil";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Perfil - Fast Food</title>
  <link rel="stylesheet" href="./css/perfil.css">
  <link rel="stylesheet" href="./css/header.css">
</head>

<body>
<?php
  $paginaAtual = 'perfil';
  include 'includes/header.php'; ?>

  <div class="container">
    <h1 class="h1">Informações da conta</h1>
    
    <div class="pai">
      <div class="usuario">
        <img src="./imgs/pessoa.png" alt="Foto de perfil" class="foto">
        <h2 class="info"><?= $usuario['nome'] ?></h2> <!-- Nome do usuário -->
      </div>
    
      <div class="informacoes">
        <form action="perfil.php" method="POST">
          <div class="campo">
            <label for="logradouro">Endereço:</label>
            <input type="text" name="logradouro" value="<?= $usuario['logradouro'] ?>" required>
          </div>

          <div class="campo">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= $usuario['email'] ?>" required readonly>
          </div>

          <div class="campo">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" value="<?= $usuario['bairro'] ?>" required>
          </div>

          <div class="campo">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?= $usuario['numero'] ?>" required>
          </div>

          <div class="campo">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha">
          </div>

          <div class="campo">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= $usuario['nome'] ?>" required>
          </div>

          <div class="campo">
            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" id="complemento" value="<?= $usuario['complemento'] ?>">
          </div>

          <div class="campo">
            <button type="submit" class="botao">Salvar alterações</button> <!-- Botão para submeter o formulário -->
          </div>
        </form>
      </div>
    </div>

    <div class="botoes">
      <button class="botao" onclick="window.history.back();">Voltar</button> <!-- Botão para voltar -->
    </div>

    <?php include 'includes/footer.php'; ?>
  </div>
</body>

</html>
