<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['permissao'] === 'admin') {
    header("Location: ../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
        <p>Escolha uma opcao para continuar.</p>

        <div class="acoes">
            <a class="btn btn-reserva" href="pacotes/pacotes.php">Reservar pacote</a>
            <a class="btn btn-logout" href="../logout.php">Logout</a>
        </div>
    </main>
</body>
</html>
