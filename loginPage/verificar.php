<?php
session_start();
require 'conexao.php';
$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

// Prepared Statement
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':usuario', $usuario);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
//if ($usuario && $senha == $usuario['senha']) {
if ($usuario && isset($usuario['senha']) && password_verify($senha, $usuario['senha'])) {
    // Login com sucesso
    $_SESSION['usuario_id']   = $usuario['ID_Usuario'];
    $_SESSION['usuario_nome'] = $usuario['usuario'];
    $_SESSION['permissao'] = $usuario['permissão'];
    if ($usuario['permissão'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: usuarios/usuario.php'); 
    }
    exit;
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Ecotrails</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="background"></div>
<header>
    <nav>
        <a href="../index.html">Voltar</a>
    </nav>
</header>
<div class="Fundo">
    <div class="Conteudo">
        <p>Usuário ou senha incorretos</p>
        <a href="index.html">Tentar novamente</a>
    </div>
</div>
</body>
</html>