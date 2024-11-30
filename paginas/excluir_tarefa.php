<?php 
    require_once 'cabecalho.php'; 
    require_once 'navbar.php';
    require_once '../funcoes/tarefas.php';
    require_once '../funcoes/usuarios.php';
    
    $id = $_GET['id'];
    $tarefa = buscarTarefaPorId($id);

    $responsavel_nome = retornaUsuarioPorId($tarefa['responsavel_id']);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            $id = intval($_POST['id']);
            if(empty($id)){
                header('Location: tarefa.php');
                exit();
            } else {
                if(excluirTarefa($id)){
                    header('Location: tarefa.php');
                    exit();
                } else {
                    $erro = "Erro ao excluir a tarefa!";
                }
            }
        } catch(Exception $e){
            $erro = "Erro: ".$e->getMessage();
        }
    }
?>

<div class="container mt-5">
    <h2>Excluir Tarefa</h2>
    
    <p>Tem certeza de que deseja excluir a tarefa abaixo?</p>
    <ul>
        <li><strong>Nome:</strong> <?=$tarefa['nome'] ?> </li>
        <li><strong>Descrição:</strong> <?=$tarefa['descricao'] ?> </li>
        <li><strong>Status:</strong> <?=$tarefa['status'] ?> </li>
        <li><strong>Responsável:</strong> <?=$responsavel_nome['nome']?> </li>
        <li><strong>Projeto:</strong> <?=$tarefa['nome_projeto'] ?> </li>
    </ul>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="confirmar" class="btn btn-danger">Excluir</button>
        <a href="tarefa.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
