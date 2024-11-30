<?php 
    require_once 'cabecalho.php'; 
    require_once 'navbar.php';
    require_once '../funcoes/projetos.php';
    require_once '../funcoes/usuarios.php';
    
    $id = $_GET['id'];
    if(!$id){
        header('Location: projetos.php');
        exit();
    }

    $projeto = buscarProjetoPorId($id);
    if(!$projeto){
        header('Location: projetos.php');
        exit();
    }

    $usuarios = todosUsuarios();
    $erro = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];
            $status = $_POST['status'];
            $criador_id = intval($_POST['criador_id']);
            $responsavel_id = intval($_POST['responsavel_id']);
            $id = intval($_POST['id']);

            if(empty($nome)){
                $erro = "Preencha os campos obrigatórios!";
            } else{
                if(alterarProjeto($id, $nome, $descricao, $data_inicio, $data_fim, $status, $responsavel_id)){
                    header('Location: projetos.php');
                    exit();
                } else{
                    $erro = "Erro ao alterar o projeto!";
                }
            }

        } catch(Exception $e){
            $erro = "Erro:" .$e->getMessage();
        }
    }
?>

<div class="container mt-5">
    <h2>Editar Projeto</h2>

    <?php if($erro): ?>
        <div class="alert alert-danger">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" value="<?= $projeto['nome'] ?>" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required><?= $projeto['descricao'] ?></textarea>
        </div>
        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="datetime-local" name="data_inicio" id="data_inicio" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($projeto['data_inicio'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="datetime-local" name="data_fim" id="data_fim" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($projeto['data_fim'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="em andamento" <?= $projeto['status'] == 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="concluído" <?= $projeto['status'] == 'concluído' ? 'selected' : '' ?>>Concluído</option>
                <option value="cancelado" <?= $projeto['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        <div class="mb-3">
            <div class="mb-3">
            <label for="responsavel_id" class="form-label">Responsável do Projeto</label>
            <select name="responsavel_id" id="responsavel_id" class="form-control" required>
                <?php foreach($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>"
                        <?= $u['id'] == $projeto['responsavel_id'] ? 'selected' : '' ?>>
                    <?= $u['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Projeto</button>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
