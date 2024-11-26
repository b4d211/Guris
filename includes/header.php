<header>
    <nav id="navbar">
        <!-- Logo -->
        <img class="guris" src="../imgs/GURIS.png" alt="Logo">

        <!-- Lista de navegação -->
        <ul id="nav_list">
            <?php if (isset($_SESSION['role'])): ?>
                <!-- Menu para clientes -->
                <?php if ($_SESSION['role'] === 'cliente'): ?>
                    <li class="<?= ($paginaAtual == 'index') ? 'ativo' : ''; ?>"><a href="/usuario/index.php">Home</a></li> <!-- Home do usuário -->
                    <li class="<?= ($paginaAtual == 'carrinho') ? 'ativo' : ''; ?>"><a href="/usuario/carrinho.php">Carrinho</a></li> <!-- Carrinho -->
                    <li class="<?= ($paginaAtual == 'compras') ? 'ativo' : ''; ?>"><a href="/usuario/compras.php">Meus Pedidos</a></li> <!-- Meus Pedidos -->
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <!-- Menu para administradores -->
                    <li class="<?= ($paginaAtual == 'index') ? 'ativo' : ''; ?>"><a href="/admin/index.php">Home</a></li> <!-- Home do admin -->
                    <li class="<?= ($paginaAtual == 'vendas') ? 'ativo' : ''; ?>"><a href="/admin/vendas.php">Gerenciar Pedidos</a></li> <!-- Gerenciar Pedidos -->
                    <li class="<?= ($paginaAtual == 'adicionar_produto') ? 'ativo' : ''; ?>"><a href="/admin/adicionar_produto.php">Adicionar Produto</a></li> <!-- Adicionar Produto -->
                <?php endif; ?>
                <!-- Link para Perfil (comum a todos) -->
                <li class="<?= ($paginaAtual == 'perfil') ? 'ativo' : ''; ?>"><a href="/perfil.php">Perfil</a></li>
            <?php else: ?>
                <!-- Se o usuário não estiver logado, mostra o link para Cadastro -->
                <li class="<?= ($paginaAtual == 'cadastro') ? 'ativo' : ''; ?>"><a href="/cadastro.php">Cadastrar</a></li>
            <?php endif; ?>
        </ul>

        <!-- Botão de Login/Sair -->
        <?php if (isset($_SESSION['role'])): ?>
            <!-- Se o usuário está logado, mostra o botão Sair -->
            <a href="/logout.php"><button class="btn-default">Sair</button></a>
        <?php else: ?>
            <!-- Se o usuário não está logado, mostra o botão Entrar -->
            <a href="/login.php"><button class="btn-default">Entrar</button></a>
        <?php endif; ?>
    </nav>
</header>