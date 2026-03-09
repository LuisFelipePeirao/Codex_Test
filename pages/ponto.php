<?php
$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunoId = (int) post('aluno_id');
    $escalaId = (int) post('escala_id');
    $tipo = post('tipo');
    $observacao = post('observacao');

    if ($alunoId <= 0 || $escalaId <= 0 || !in_array($tipo, ['entrada', 'saida'], true)) {
        setFlash('Dados de registro inválidos.', 'danger');
        redirect('ponto');
    }

    $stmt = $pdo->prepare('INSERT INTO registros_ponto (aluno_id, escala_id, tipo, observacao) VALUES (?, ?, ?, ?)');
    $stmt->execute([$alunoId, $escalaId, $tipo, $observacao !== '' ? $observacao : null]);
    setFlash('Ponto registrado com sucesso.');
    redirect('ponto');
}

$alunos = $pdo->query('SELECT id, nome, matricula FROM alunos ORDER BY nome')->fetchAll();
$escalas = $pdo->query('SELECT id, nome, hora_inicio, hora_fim FROM escalas ORDER BY nome')->fetchAll();

$registros = $pdo->query(
    'SELECT rp.id, a.nome AS aluno, a.matricula, e.nome AS escala, rp.tipo, rp.observacao, rp.registrado_em
     FROM registros_ponto rp
     JOIN alunos a ON a.id = rp.aluno_id
     JOIN escalas e ON e.id = rp.escala_id
     ORDER BY rp.registrado_em DESC
     LIMIT 20'
)->fetchAll();
?>
<h1 class="h3 mb-3">Registro de Ponto</h1>
<div class="card mb-4">
    <div class="card-body">
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Aluno</label>
                <select name="aluno_id" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php foreach ($alunos as $aluno): ?>
                        <option value="<?= (int) $aluno['id'] ?>"><?= htmlspecialchars($aluno['nome']) ?> (<?= htmlspecialchars($aluno['matricula']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Escala</label>
                <select name="escala_id" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php foreach ($escalas as $escala): ?>
                        <option value="<?= (int) $escala['id'] ?>"><?= htmlspecialchars($escala['nome']) ?> (<?= htmlspecialchars($escala['hora_inicio']) ?>-<?= htmlspecialchars($escala['hora_fim']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Observação</label>
                <input type="text" name="observacao" class="form-control">
            </div>
            <div class="col-12 d-grid">
                <button class="btn btn-primary">Registrar ponto</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Últimos registros</div>
    <div class="card-body table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Aluno</th>
                <th>Matrícula</th>
                <th>Escala</th>
                <th>Tipo</th>
                <th>Data/Hora</th>
                <th>Observação</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['aluno']) ?></td>
                    <td><?= htmlspecialchars($registro['matricula']) ?></td>
                    <td><?= htmlspecialchars($registro['escala']) ?></td>
                    <td><span class="badge text-bg-<?= $registro['tipo'] === 'entrada' ? 'success' : 'secondary' ?>"><?= htmlspecialchars(ucfirst($registro['tipo'])) ?></span></td>
                    <td><?= htmlspecialchars($registro['registrado_em']) ?></td>
                    <td><?= htmlspecialchars((string) $registro['observacao']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
