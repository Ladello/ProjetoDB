<?php
require_once 'cabecalho.php';
require_once 'navbar.php';
require_once '../funcoes/projetos.php';
require_once '../funcoes/usuarios.php';

if (!isset($_SESSION['acesso']) || $_SESSION['acesso'] !== true) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$nivel_usuario = $_SESSION['nivel'];
$usuario_id = RetornaIdPorNome($usuario);
$projetos_tarefas = buscarProjetosTarefasUsuario($usuario_id, $nivel_usuario);

?>
<div class="container mt-5">
    <h2>Início</h2>
    <p>Bem-vindo, <?= $usuario; ?>!</p>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Projeto</th>
                <th>Tarefa</th>
                <th>Descrição</th>
                <th>Responsável</th>
                <th>Designados</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projetos_tarefas as $item): ?>
                <tr>
                    <td><?= $item['projeto_nome']; ?></td>
                    <td><?= $item['tarefa_nome'] ?? 'Sem tarefas'; ?></td>
                    <td><?= $item['tarefa_descricao'] ?? ''; ?></td>
                    <td><?= $item['responsavel_nome'] ?? ''; ?></td>
                    <td><?= $item['designados'] ?? 'Nenhum'; ?></td>
                    <td><?= $item['tarefa_status'] ?? ''; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'rodape.php'; ?>
