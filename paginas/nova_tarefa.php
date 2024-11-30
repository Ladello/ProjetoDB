<?php
require_once 'cabecalho.php';
require_once 'navbar.php';
require_once '../funcoes/tarefas.php';
require_once '../funcoes/projetos.php';
require_once '../funcoes/usuarios.php';

$projetos = buscarProjetos();
$usuarios = todosUsuarios();

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try{
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $projeto_id = intval($_POST['projeto_id'] ?? 0);
    $responsavel_id = intval($_POST['responsavel_id'] ?? 0);
    $designados_ids = $_POST['designados_ids'] ?? []; 

    if (empty($nome) || empty($projeto_id) || empty($responsavel_id)) {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        if (criarTarefa($nome, $descricao, $projeto_id, $responsavel_id, $designados_ids)) {
            header('Location: tarefa.php');
            exit();
        } else {
            $erro = "Erro ao criar a tarefa.";
        }
    }

    } catch (Exception $e){
        $erro = "Erro: ".$e->getMessage();
    }
}
?>

<div class="container mt-5">
    <h2>Criar Tarefa</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Tarefa</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="projeto_id" class="form-label">Projeto</label>
            <select name="projeto_id" id="projeto_id" class="form-select" required>
                <option value="">Selecione um projeto</option>
                <?php foreach ($projetos as $projeto): ?>
                    <option value="<?= $projeto['id'] ?>"><?= $projeto['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="responsavel_id" class="form-label">Responsável</label>
            <select name="responsavel_id" id="responsavel_id" class="form-select" required>
                <option value="">Selecione o responsável</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="designados" class="form-label">Designados</label>
            <div id="designados">
                <?php foreach ($usuarios as $usuario): ?>
                    <div>
                        <input type="checkbox" name="designados_ids[]" value="<?= $usuario['id'] ?>" id="designado_<?= $usuario['id'] ?>">
                        <label for="designado_<?= $usuario['id'] ?>"><?= $usuario['nome'] ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="tarefa.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once 'rodape.php'; ?>