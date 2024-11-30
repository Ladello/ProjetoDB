<?php

declare(strict_types=1);
require_once '../config/bancodedados.php';

function buscarProjetos() : array {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM projeto");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProjetoPorId(int $id) : ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projeto WHERE id = ?");
    $stmt->execute([$id]);
    $projeto = $stmt->fetch(PDO::FETCH_ASSOC);
    return $projeto ? $projeto : null;
}

function buscarProjetosTarefasUsuario(int $usuario_id, string $nivel_usuario): array {
    global $pdo;

    if ($nivel_usuario === 'adm') {
        $stmt = $pdo->query(
            "SELECT p.id AS projeto_id, 
                    p.nome AS projeto_nome, 
                    t.id AS tarefa_id, 
                    t.nome AS tarefa_nome, 
                    t.descricao AS tarefa_descricao, 
                    t.status AS tarefa_status,
                    resp.nome AS responsavel_nome,
                    GROUP_CONCAT(desig.nome SEPARATOR ', ') AS designados
             FROM projeto p
             LEFT JOIN tarefa t ON t.projeto_id = p.id
             LEFT JOIN usuario resp ON t.responsavel_id = resp.id
             LEFT JOIN tarefa_designado td ON td.tarefa_id = t.id
             LEFT JOIN usuario desig ON td.usuario_id = desig.id
             GROUP BY p.id, t.id"
        );
    } else {
        $stmt = $pdo->prepare(
            "SELECT p.id AS projeto_id, 
                    p.nome AS projeto_nome, 
                    t.id AS tarefa_id, 
                    t.nome AS tarefa_nome, 
                    t.descricao AS tarefa_descricao, 
                    t.status AS tarefa_status,
                    resp.nome AS responsavel_nome,
                    GROUP_CONCAT(desig.nome SEPARATOR ', ') AS designados
             FROM projeto p
             LEFT JOIN tarefa t ON t.projeto_id = p.id
             LEFT JOIN usuario resp ON t.responsavel_id = resp.id
             LEFT JOIN tarefa_designado td ON td.tarefa_id = t.id
             LEFT JOIN usuario desig ON td.usuario_id = desig.id
             WHERE p.responsavel_id = ?
                OR desig.id = ?
             GROUP BY p.id, t.id"
        );
        $stmt->execute([$usuario_id, $usuario_id]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function criarProjeto(string $nome, string $descricao, string $data_inicio, string $data_fim, string $status, int $responsavel_id) : bool {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO projeto (nome, descricao, data_inicio, data_fim, status, responsavel_id) 
        VALUES (?, ?, ?, ?, ?, ?)");
    
    return $stmt->execute([$nome, $descricao, $data_inicio, $data_fim, $status, $responsavel_id]);
}

function alterarProjeto(int $id, string $nome, string $descricao, string $data_inicio, string $data_fim, string $status, int $responsavel_id) : bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE projeto SET nome = ?, descricao = ?, data_inicio = ?, data_fim = ?, status = ?, responsavel_id = ? WHERE id = ?");
    return $stmt->execute([$nome, $descricao, $data_inicio, $data_fim, $status, $responsavel_id, $id]);
}

function excluirProjeto(int $id) : bool {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM projeto WHERE id = ?");
    return $stmt->execute([$id]);
}
