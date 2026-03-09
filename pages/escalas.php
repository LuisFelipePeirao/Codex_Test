<?php
$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = post('action');

    if ($action === 'create_schedule') {
        $nome = post('nome');
        $inicio = post('hora_inicio');
        $fim = post('hora_fim');

        if ($nome === '' || $inicio === '' || $fim === '') {
            setFlash('Preencha todos os dados da escala.', 'danger');
            redirect('escalas');
        }

        $stmt = $pdo->prepare('INSERT INTO escalas (nome, hora_inicio, hora_fim) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $inicio, $fim]);
        setFlash('Escala criada com sucesso.');
        redirect('escalas');
    }

    if ($action === 'link_group') {
        $escalaId = (int) post('escala_id');
        $grupoId = (int) post('grupo_id');

        $stmt = $pdo->prepare('INSERT IGNORE INTO escalas_grupos (escala_id, grupo_id) VALUES (?, ?)');
        $stmt->execute([$escalaId, $grupoId]);
        setFlash('Grupo vinculado à escala.');
        redirect('escalas');
    }
}

if (isset($_GET['unlink_group'], $_GET['escala'])) {
    $escalaId = (int) $_GET['escala'];
    $grupoId = (int) $_GET['unlink_group'];
    $stmt = $pdo->prepare('DELETE FROM escalas_grupos WHERE escala_id = ? AND grupo_id = ?');
    $stmt->execute([$escalaId, $grupoId]);
    setFlash('Grupo desvinculado da escala.');
    redirect('escalas');
}

$escalas = $pdo->query('SELECT * FROM escalas ORDER BY nome')->fetchAll();
$grupos = $pdo->query('SELECT * FROM grupos ORDER BY nome')->fetchAll();
$vinculos = $pdo->query('SELECT eg.escala_id, g.id, g.nome FROM escalas_grupos eg JOIN grupos g ON g.id = eg.grupo_id ORDER BY g.nome')->fetchAll();

$gruposPorEscala = [];
foreach ($vinculos as $vinculo) {
    $gruposPorEscala[$vinculo['escala_id']][] = $vinculo;
}
?>
<h1 class="h3 mb-3">Escalas</h1>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Nova escala</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="create_schedule">
                    <div class="col-12">
                        <label class="form-label">Nome da escala</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Hora início</label>
                        <input type="time" name="hora_inicio" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Hora fim</label>
                        <input type="time" name="hora_fim" class="form-control" required>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-primary">Criar escala</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Vincular grupo à escala</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="link_group">
                    <div class="col-12">
                        <label class="form-label">Escala</label>
                        <select name="escala_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($escalas as $escala): ?>
                                <option value="<?= (int) $escala['id'] ?>"><?= htmlspecialchars($escala['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Grupo</label>
                        <select name="grupo_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= (int) $grupo['id'] ?>"><?= htmlspecialchars($grupo['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 d-grid">
                        <button class="btn btn-success">Vincular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <?php foreach ($escalas as $escala): ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <strong><?= htmlspecialchars($escala['nome']) ?></strong>
                    <span><?= htmlspecialchars($escala['hora_inicio']) ?> às <?= htmlspecialchars($escala['hora_fim']) ?></span>
                </div>
                <div class="card-body">
                    <?php $lista = $gruposPorEscala[$escala['id']] ?? []; ?>
                    <?php if (!$lista): ?>
                        <p class="text-muted mb-0">Nenhum grupo vinculado.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($lista as $grupo): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($grupo['nome']) ?></span>
                                    <a href="index.php?page=escalas&escala=<?= (int) $escala['id'] ?>&unlink_group=<?= (int) $grupo['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Desvincular grupo?">Desvincular</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
