<?php
$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = post('action');

    if ($action === 'create_group') {
        $nome = post('nome');
        $descricao = post('descricao');

        if ($nome === '') {
            setFlash('Informe o nome do grupo.', 'danger');
            redirect('grupos');
        }

        $stmt = $pdo->prepare('INSERT INTO grupos (nome, descricao) VALUES (?, ?)');
        $stmt->execute([$nome, $descricao !== '' ? $descricao : null]);
        setFlash('Grupo criado com sucesso.');
        redirect('grupos');
    }

    if ($action === 'add_student') {
        $grupoId = (int) post('grupo_id');
        $alunoId = (int) post('aluno_id');

        $stmt = $pdo->prepare('INSERT IGNORE INTO grupos_alunos (grupo_id, aluno_id) VALUES (?, ?)');
        $stmt->execute([$grupoId, $alunoId]);
        setFlash('Aluno adicionado ao grupo.');
        redirect('grupos');
    }
}

if (isset($_GET['remove_member'], $_GET['grupo'])) {
    $grupoId = (int) $_GET['grupo'];
    $alunoId = (int) $_GET['remove_member'];
    $stmt = $pdo->prepare('DELETE FROM grupos_alunos WHERE grupo_id = ? AND aluno_id = ?');
    $stmt->execute([$grupoId, $alunoId]);
    setFlash('Aluno removido do grupo.');
    redirect('grupos');
}

$grupos = $pdo->query('SELECT * FROM grupos ORDER BY nome')->fetchAll();
$alunos = $pdo->query('SELECT * FROM alunos ORDER BY nome')->fetchAll();
$membros = $pdo->query('SELECT ga.grupo_id, a.id, a.nome, a.matricula FROM grupos_alunos ga JOIN alunos a ON a.id = ga.aluno_id ORDER BY a.nome')->fetchAll();

$membrosPorGrupo = [];
foreach ($membros as $membro) {
    $membrosPorGrupo[$membro['grupo_id']][] = $membro;
}
?>
<h1 class="h3 mb-3">Grupos</h1>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Novo grupo</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="create_group">
                    <div class="col-12">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control"></textarea>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-primary">Criar grupo</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Adicionar aluno a grupo</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="add_student">
                    <div class="col-12">
                        <label class="form-label">Grupo</label>
                        <select name="grupo_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= (int) $grupo['id'] ?>"><?= htmlspecialchars($grupo['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Aluno</label>
                        <select name="aluno_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($alunos as $aluno): ?>
                                <option value="<?= (int) $aluno['id'] ?>"><?= htmlspecialchars($aluno['nome']) ?> (<?= htmlspecialchars($aluno['matricula']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-success">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <?php foreach ($grupos as $grupo): ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($grupo['nome']) ?></span>
                    <small class="text-muted"><?= htmlspecialchars((string) $grupo['descricao']) ?></small>
                </div>
                <div class="card-body">
                    <?php $lista = $membrosPorGrupo[$grupo['id']] ?? []; ?>
                    <?php if (!$lista): ?>
                        <p class="text-muted mb-0">Nenhum aluno no grupo.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($lista as $membro): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($membro['nome']) ?> (<?= htmlspecialchars($membro['matricula']) ?>)</span>
                                    <a href="index.php?page=grupos&grupo=<?= (int) $grupo['id'] ?>&remove_member=<?= (int) $membro['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Remover aluno deste grupo?">Remover</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
