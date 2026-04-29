<?php
session_start();
require '../../conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['permissao'] === 'admin') {
    header("Location: ../../index.html");
    exit;
}

$mensagem = '';
$tipoMensagem = '';

$pacotes = [];

function valorColuna(array $linha, array $nomesPossiveis, $padrao = null)
{
    foreach ($nomesPossiveis as $nome) {
        if (array_key_exists($nome, $linha)) {
            return $linha[$nome];
        }
    }
    // Fallback desativado:
    // return $padrao;
    return null;
}

try {
    $stmtPacotes = $pdo->query("SELECT ID_Pacote, nome, Destino, preco FROM Pacotes.pacotes ORDER BY ID_Pacote ASC");
    $linhas = $stmtPacotes->fetchAll(PDO::FETCH_ASSOC);

    foreach ($linhas as $linha) {
        $pacotes[] = [
            // Fallbacks desativados:
            // 'id' => (int) valorColuna($linha, ['ID_Pacote', 'id_pacote'], 0),
            // 'nome' => (string) valorColuna($linha, ['nome', 'Nome'], 'Pacote'),
            // 'origem' => (string) valorColuna($linha, ['Destino', 'destino'], ''),
            // 'preco' => valorColuna($linha, ['preco', 'Preço'], '')
            'id' => (int) valorColuna($linha, ['ID_Pacote']),
            'nome' => (string) valorColuna($linha, ['nome']),
            'origem' => (string) valorColuna($linha, ['Destino']),
            'preco' => valorColuna($linha, ['preco'])
        ];
    }
} catch (PDOException $e) {
    $mensagem = 'Nao foi possivel carregar pacotes da tabela Pacotes.pacotes.';
    $tipoMensagem = 'erro';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacotes - Area do Usuario</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; padding: 24px; background: #203a43; color: #fff; }
        .container { max-width: 920px; margin: 0 auto; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .acoes { display: flex; gap: 8px; }
        .btn { text-decoration: none; color: #fff; padding: 10px 14px; border-radius: 8px; font-weight: bold; }
        .btn-voltar { background: #3498db; }
        .btn-logout { background: #e74c3c; }
        .btn-carrinho { background:rgb(7, 67, 14); }
        .mensagem { padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        .sucesso { background: rgba(46, 204, 113, 0.25); }
        .erro { background: rgba(231, 76, 60, 0.25); }
        .lista { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
        .card { background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 14px; }
        .card h3 { margin-top: 0; margin-bottom: 6px; }
        .preco { font-weight: bold; margin: 8px 0 12px; }
        button { border: 0; border-radius: 8px; padding: 10px 12px; background: #2ecc71; color: #fff; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <main class="container">
        <div class="topo">
            <h1>Pacotes disponiveis</h1>
            <div class="acoes">
                <a class="btn btn-voltar" href="../usuario.php">Voltar</a>
                <a class="btn btn-logout" href="../../logout.php">Logout</a>
            </div>
        </div>

        <?php if ($mensagem !== ''): ?>
            <p class="mensagem <?php echo htmlspecialchars($tipoMensagem); ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </p>
        <?php endif; ?>

        <section class="lista">
            <?php if (count($pacotes) === 0): ?>
                <article class="card">
                    <h3>Nenhum pacote encontrado</h3>
                    <p>Adicione registros na tabela pacotes.</p>
                </article>
            <?php else: ?>
                <?php foreach ($pacotes as $pacote): ?>
                    <article class="card">
                        <h3><?php echo htmlspecialchars($pacote['nome']); ?></h3>
                        <p><?php echo htmlspecialchars($pacote['origem']); ?></p>
                        <?php if ($pacote['preco'] !== null && $pacote['preco'] !== ''): ?>
                            <p class="preco">R$ <?php echo htmlspecialchars(number_format((float) $pacote['preco'], 2, ',', '.')); ?></p>
                        <?php endif; ?>
                        <a class="btn btn-carrinho" href="reservas/index.php?pacote_id=<?php echo (int) $pacote['id']; ?>">Reservar</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
