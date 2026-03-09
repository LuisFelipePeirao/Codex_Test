<?php
$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = post('nome');
    $matricula = post('matricula');
    $email = post('email');

    if ($nome === '' || $matricula === '') {
        setFlash('Nome e matrícula são obrigatórios.', 'danger');
        redirect('alunos');
    }

    $stmt = $pdo->prepare('INSERT INTO alunos (nome, matricula, email) VALUES (?, ?, ?)');
    $stmt->execute([$nome, $matricula, $email !== '' ? $email : null]);

    setFlash('Aluno cadastrado com sucesso.');
    redirect('alunos');
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM alunos WHERE id = ?');
    $stmt->execute([$id]);
    setFlash('Aluno removido com sucesso.');
    redirect('alunos');
}

$alunos = $pdo->query('SELECT * FROM alunos ORDER BY nome')->fetchAll();
?>
<h1 class="h3 mb-3">Cadastro de Alunos</h1>
<div class="card mb-4">
    <div class="card-body">
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Matrícula</label>
                <input type="text" name="matricula" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-1 d-grid">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>E-mail</th>
                <th class="text-end">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <td><?= htmlspecialchars($aluno['nome']) ?></td>
                    <td><?= htmlspecialchars($aluno['matricula']) ?></td>
                    <td><?= htmlspecialchars((string) $aluno['email']) ?></td>
                    <td class="text-end">
                        <a href="index.php?page=alunos&delete=<?= (int) $aluno['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Confirma a remoção deste aluno?">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
