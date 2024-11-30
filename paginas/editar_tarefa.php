<?php 
    require_once 'cabecalho.php'; 
    require_once 'navbar.php';
    require_once '../funcoes/tarefas.php';
    require_once '../funcoes/usuarios.php';

    $id = $_GET['id'];
    if (!$id) {
        header('Location: tarefa.php');
        exit();
    }

    $tarefa = buscarTarefaPorId($id);
    if (!$tarefa) {
        header('Location: tarefa.php');
        exit();
    }

    $usuarios = todosUsuarios();
    $erro = "";

    $designados_atual = array_column($tarefa['designados'], 'id');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $responsavel_id = intval($_POST['responsavel_id']);
            $status = $_POST['status'];
            $designados_incluir = $_POST['designados_incluir'] ?? [];
            $designados_excluir = $_POST['designados_excluir'] ?? [];

            if (empty($nome)) {
                $erro = "Preencha os campos obrigatórios!";
            } else {
                if (alterarTarefa($id, $nome, $descricao, $responsavel_id, $designados_incluir, $designados_excluir, $status)) {
                    header('Location: tarefa.php');
                    exit();
                } else {
                    $erro = "Erro ao alterar a tarefa!";
                }
            }

        } catch (Exception $e) {
            $erro = "Erro: " . $e->getMessage();
        }
    }
?>

<div class="container mt-5">
    <h2>Editar Tarefa</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>" />


        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" value="<?= $tarefa['nome'] ?>" id="nome" class="form-control" required>
        </div>


        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required><?= $tarefa['descricao'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="responsavel_id" class="form-label">Responsável</label>
            <select name="responsavel_id" id="responsavel_id" class="form-control" required>
                <option value="">Selecione o Responsável</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $tarefa['responsavel_id'] == $u['id'] ? 'selected' : '' ?>>
                        <?= $u['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="em andamento" <?= $tarefa['status'] == 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="concluído" <?= $tarefa['status'] == 'concluído' ? 'selected' : '' ?>>Concluído</option>
                <option value="cancelado" <?= $tarefa['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <div class="mt-5">
            <label for="designados_atual" class="form-label"><h4>Designados Atuais</h4></label>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarefa['designados'] as $designado): ?>
                        <tr>
                            <td><?= $designado['nome'] ?></td>
                            <td>
                                <input type="checkbox" name="designados_excluir[]" value="<?= $designado['id'] ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label for="designados_incluir" class="form-label">Adicionar Designados</label>
            <select name="designados_incluir[]" id="designados_incluir" class="form-control" multiple>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" 
                        <?= in_array($u['id'], $designados_atual) ? 'disabled' : '' ?>>
                        <?= $u['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
