<?php
    require_once 'cabecalho.php'; 
    require_once 'navbar.php'; 
    require_once '../funcoes/usuarios.php';

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: usuarios.php');
        exit();
    }

    $id = intval($_GET['id']);
    $usuario = retornaUsuarioPorId($id);

    if (!$usuario) {
        header('Location: usuarios.php');
        exit();
    }

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $nivel = $_POST['nivel'];

            if (empty($nome) || empty($email) || empty($nivel)) {
                $erro = 'Preencha todos os campos obrigatórios!';
            } else {
                if (alterarUsuario($id, $nome, $email, $nivel)) {
                    header('Location: usuarios.php');
                    exit();
                } else {
                    $erro = 'Erro ao atualizar o usuário!';
                }
            }
        } catch (Exception $e) {
            $erro = 'Erro: ' . $e->getMessage();
        }
    }
?>

<div class="container mt-5">
    <h2>Editar Usuário</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>" />

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= $usuario['nome'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $usuario['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="nivel" class="form-label">Nível</label>
            <select name="nivel" id="nivel" class="form-control" required>
                <option value="adm" <?= $usuario['nivel'] == 'adm' ? 'selected' : '' ?>>Administrador</option>
                <option value="colaborador" <?= $usuario['nivel'] == 'colaborador' ? 'selected' : '' ?>>Colaborador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
        <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once 'rodape.php'; ?>
