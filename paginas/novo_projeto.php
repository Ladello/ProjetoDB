<?php 
    require_once 'cabecalho.php'; 
    require_once 'navbar.php';
    require_once '../funcoes/projetos.php'; 
    require_once '../funcoes/usuarios.php';

    $erro = ' ';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];
            $status = $_POST['status'];
            $responsavel_id = intval($_POST['responsavel_id']);


            if ((empty($nome)) || empty($descricao)){
                $erro = "Informe os valores obrigatórios!";
            } else{
                if (criarProjeto($nome, $descricao, $data_inicio, $data_fim, $status, $responsavel_id)){
                    header('Location: projetos.php');
                    exit();
                } else{
                    $erro = "Erro ao inserir o projeto";
                }
            }

        } catch (Exception $e){
            $erro = "Erro: ".$e->getMessage();
        }
    }

    $usuarios = todosUsuarios(); 
?>

<div class="container mt-5">
    <h2>Criar Novo Projeto</h2>

    <?php if(!empty($erro)):?>
        <p class= "text-danger"><?= $erro ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="datetime-local" name="data_inicio" id="data_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="datetime-local" name="data_fim" id="data_fim" class="form-control">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="em andamento">Em Andamento</option>
                <option value="concluído">Concluído</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="responsavel_id" class="form-label">Responsável</label>
            <select name="responsavel_id" id="responsavel_id" class="form-select" required>
                <?php foreach($usuarios as $usuario) : ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Criar Projeto</button>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
