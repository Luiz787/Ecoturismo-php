<?php
require 'conexao.php';

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) :  '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    $cpf = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';

    if ($usuario === '' || $senha === '' || $cpf === '' || $telefone === '') {
        $camposFaltando = [];
        if ($usuario === '') {
            $camposFaltando[] = 'usuario';
        }
        if ($senha === '') {
            $camposFaltando[] = 'senha';
        }
        if ($cpf === '') {
            $camposFaltando[] = 'CPF';
        }
        if ($telefone === '') {
            $camposFaltando[] = 'telefone';
        }
        $mensagemErro = 'Campos faltando: ' . implode(', ', $camposFaltando) . '.';
    } elseif (preg_match('/\d/', $usuario)) {
        $mensagemErro = 'O nome de usuario nao pode conter numeros.';
    } else {
        $cpfNumerico = preg_replace('/\D/', '', $cpf);
        $telefoneNumerico = preg_replace('/\D/', '', $telefone);

        if (strlen($cpfNumerico) !== 11) {
            $mensagemErro = 'CPF invalido. Informe 11 digitos.';
        } elseif (strlen($telefoneNumerico) < 10 || strlen($telefoneNumerico) > 11) {
            $mensagemErro = 'Telefone invalido. Informe DDD + numero.';
        } else {
            $sqlVerifica = "SELECT ID_Usuario FROM usuarios WHERE usuario = :usuario";
            $stmtVerifica = $pdo->prepare($sqlVerifica);
            $stmtVerifica->bindValue(':usuario', $usuario);
            $stmtVerifica->execute();

            if ($stmtVerifica->fetch()) {
                $mensagemErro = 'Este usuario ja existe.';
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $sqlCadastro = "INSERT INTO usuarios (usuario, senha, Telefone, CPF, permissão) VALUES (:usuario, :senha, :telefone, :cpf, :permissao)";
                $stmtCadastro = $pdo->prepare($sqlCadastro);
                $stmtCadastro->bindValue(':usuario', $usuario);
                $stmtCadastro->bindValue(':senha', $senhaHash);
                $stmtCadastro->bindValue(':telefone', $telefoneNumerico);
                $stmtCadastro->bindValue(':cpf', $cpfNumerico);
                $stmtCadastro->bindValue(':permissao', 'usuario');
                $stmtCadastro->execute();
                
                $mensagemErro = 'Cadastro realizado com sucesso.';

                header('Location: index.html');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Ecotrails</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background"></div>
    <header>
        <nav>
            <a href="index.html">Voltar para login</a>
        </nav>
    </header>

    <form class="Fundo" action="cadastro.php" method="POST">
        <div class="Conteudo">
            <h2>Criar conta</h2>
            <?php if ($mensagemErro !== ''): ?>
                <p><?php echo htmlspecialchars($mensagemErro); ?></p>
            <?php endif; ?>
            <input type="text" id="usuario" name="usuario" placeholder="Usuário" pattern="[A-Za-zÀ-ÿ\s]+" title="Use apenas letras." required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="text" id="cpf" name="cpf" placeholder="CPF" maxlength="11" inputmode="numeric" pattern="[0-9]{11}" required>
            <input type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="11" inputmode="numeric" pattern="[0-9]{10,11}" required>
            <button type="submit">Cadastrar</button>
        </div>
    </form>
    <script>
        function permitirApenasNumeros(evento) {
            evento.target.value = evento.target.value.replace(/\D/g, '');
        }

        function permitirApenasLetras(evento) {
            evento.target.value = evento.target.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');
        }

        document.getElementById('usuario').addEventListener('input', permitirApenasLetras);
        document.getElementById('cpf').addEventListener('input', permitirApenasNumeros);
        document.getElementById('telefone').addEventListener('input', permitirApenasNumeros);
    </script>
</body>
</html>
