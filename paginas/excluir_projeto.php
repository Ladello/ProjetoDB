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

    $responsavel_nome = retornaUsuarioPorId($projeto['responsavel_id']);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try{
            $id = intval($_POST['id']);
            if(empty($id)){
                header('Location: projetos.php');
                exit();
            }else{
                if(excluirProjeto($id)){
                    header('Location: projetos.php');
                    exit();
                } else{
                    $erro = "Erro ao excluir o projeto!";
                }
            }
        } catch(Exception $e){
            $erro = "Erro: ".$e->getMessage();
        }
    }
?>

<div class="container mt-5">
    <h2>Excluir Projeto</h2>
    
    <p>Tem certeza de que deseja excluir o projeto abaixo?</p>
    <ul>
        <li><strong>Nome:</strong> <?=$projeto['nome'] ?> </li>
        <li><strong>Descrição:</strong> <?=$projeto['descricao'] ?> </li>
        <li><strong>Data de Início:</strong> <?=$projeto['data_inicio'] ?> </li>
        <li><strong>Data de Fim:</strong> <?=$projeto['data_fim'] ?> </li>
        <li><strong>Status:</strong> <?=$projeto['status'] ?> </li>
        <li><strong>Responsável:</strong> <?=$responsavel_nome['nome'] ?> </li>
    </ul>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="confirmar" class="btn btn-danger">Excluir</button>
        <a href="projetos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
