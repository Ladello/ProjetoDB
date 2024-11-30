<?php
require_once 'cabecalho.php';
require_once 'navbar.php';
require_once '../funcoes/tarefas.php';
require_once '../funcoes/projetos.php';

$projetos = buscarProjetos();
$tarefas = [];
$projeto_selecionado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projeto_selecionado = intval($_POST['projeto_id']);
    $tarefas = buscarTarefasPorProjeto($projeto_selecionado);
}
?>

<div class="container mt-5">
    <h2>Gerenciar Tarefas</h2>
    
    <form method="post" class="mb-4">
        <div class="mb-4">
            <a href="nova_tarefa.php" class="btn btn-success">Criar Nova Tarefa</a>
        </div>

        <div class="mb-3">
            <label for="projeto_id" class="form-label">Selecione um Projeto</label>
            <select name="projeto_id" id="projeto_id" class="form-select" required>
                <option value="">Selecione um projeto</option>
                <?php foreach ($projetos as $projeto): ?>
                    <option value="<?= $projeto['id'] ?>" <?= $projeto_selecionado === $projeto['id'] ? 'selected' : '' ?>>
                        <?= $projeto['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar Tarefas</button>
    </form>

    <?php if ($tarefas): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Responsável</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tarefas as $tarefa): ?>
                    <tr>
                        <td><?= $tarefa['id'] ?></td>
                        <td><?= $tarefa['nome'] ?></td>
                        <td><?= $tarefa['descricao'] ?></td>
                        <td><?= $tarefa['nome_responsavel'] ?></td>
                        <td><?= $tarefa['status'] ?></td>
                        <td>
                            <a href="editar_tarefa.php?id=<?= $tarefa['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="excluir_tarefa.php?id=<?= $tarefa['id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Nenhuma tarefa encontrada.</div>
    <?php endif; ?>
</div>

<?php require_once 'rodape.php'; ?>
