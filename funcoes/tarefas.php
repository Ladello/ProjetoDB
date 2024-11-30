<?php

declare(strict_types=1);
require_once '../config/bancodedados.php';

// todas as tarefas
function buscarTarefas(): array {
    global $pdo;
    $stmt = $pdo->query(
        "SELECT t.*, p.nome AS nome_projeto, u.nome AS nome_responsavel FROM tarefa t
         INNER JOIN projeto p ON p.id = t.projeto_id
         INNER JOIN usuario u ON u.id = t.responsavel_id"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarTarefaPorId(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT t.*, p.nome AS nome_projeto, u.nome AS nome_responsavel 
         FROM tarefa t
         INNER JOIN projeto p ON p.id = t.projeto_id
         INNER JOIN usuario u ON u.id = t.responsavel_id
         WHERE t.id = ?"
    );
    $stmt->execute([$id]);
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarefa) {
        return null;
    }

    $stmt_designados = $pdo->prepare(
        "SELECT u.id, u.nome 
         FROM tarefa_designado td
         INNER JOIN usuario u ON u.id = td.usuario_id
         WHERE td.tarefa_id = ?"
    );
    $stmt_designados->execute([$id]);
    $designados = $stmt_designados->fetchAll(PDO::FETCH_ASSOC);

    $tarefa['designados'] = $designados;
    return $tarefa;
}

function buscarTarefasPorProjeto(int $projeto_id): array {
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT t.*, 
                p.nome AS nome_projeto, 
                u.nome AS nome_responsavel 
         FROM tarefa t
         INNER JOIN projeto p ON p.id = t.projeto_id
         INNER JOIN usuario u ON u.id = t.responsavel_id
         WHERE t.projeto_id = ?"
    );
    $stmt->execute([$projeto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function criarTarefa(string $nome, string $descricao, int $projeto_id, int $responsavel_id, array $designados, string $status = 'Em andamento'): bool {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tarefa (nome, descricao, projeto_id, responsavel_id, status) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt->execute([$nome, $descricao, $projeto_id, $responsavel_id, $status])) {
        return false;
    }

    $tarefa_id = (int) $pdo->lastInsertId();
    $stmt_designado = $pdo->prepare("INSERT INTO tarefa_designado (tarefa_id, usuario_id) VALUES (?, ?)");
    foreach ($designados as $usuario_id) {
        if (!$stmt_designado->execute([$tarefa_id, $usuario_id])) {
            return false;
        }
    }
    return true;
}

function alterarTarefa(int $id, string $nome, string $descricao, int $responsavel_id, array $designados_adicionar, array $designados_excluir, string $status): bool {
    global $pdo;

    $stmt_projeto_id = $pdo->prepare("SELECT projeto_id FROM tarefa WHERE id = ?");
    $stmt_projeto_id->execute([$id]);
    $tarefa = $stmt_projeto_id->fetch(PDO::FETCH_ASSOC);

    if (!$tarefa) {
        return false;
    }

    $stmt = $pdo->prepare(
        "UPDATE tarefa 
         SET nome = ?, descricao = ?, responsavel_id = ?, status = ? 
         WHERE id = ?"
    );

    if (!$stmt->execute([$nome, $descricao, $responsavel_id, $status, $id])) {
        return false;
    }

    // se tiver designados para excluir
    if (!empty($designados_excluir)) {
        foreach ($designados_excluir as $usuario_id) {
            $stmt_remover = $pdo->prepare(
                "DELETE FROM tarefa_designado 
                 WHERE tarefa_id = ? AND usuario_id = ?"
            );
            $stmt_remover->execute([$id, $usuario_id]);
        }
    }

    // se tiver designados para adicionar
    if (!empty($designados_adicionar)) {
        $stmt_designado = $pdo->prepare("INSERT INTO tarefa_designado (tarefa_id, usuario_id) VALUES (?, ?)");
        foreach ($designados_adicionar as $usuario_id) {
            if (!$stmt_designado->execute([$id, $usuario_id])) {
                return false;
            }
        }
    }
    return true;
}

function excluirTarefa(int $id): bool {
    global $pdo;

    $stmt_designados = $pdo->prepare("DELETE FROM tarefa_designado WHERE tarefa_id = ?");
    if (!$stmt_designados->execute([$id])) {
        return false;
    }

    $stmt = $pdo->prepare("DELETE FROM tarefa WHERE id = ?");
    return $stmt->execute([$id]);
}
