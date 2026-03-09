<?php
$pdo = getConnection();
$totalAlunos = (int) $pdo->query('SELECT COUNT(*) FROM alunos')->fetchColumn();
$totalGrupos = (int) $pdo->query('SELECT COUNT(*) FROM grupos')->fetchColumn();
$totalEscalas = (int) $pdo->query('SELECT COUNT(*) FROM escalas')->fetchColumn();
$totalRegistros = (int) $pdo->query('SELECT COUNT(*) FROM registros_ponto')->fetchColumn();
?>
<h1 class="h3 mb-4">Dashboard</h1>
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h2 class="h6">Alunos</h2>
                <p class="display-6 mb-0"><?= $totalAlunos ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h2 class="h6">Grupos</h2>
                <p class="display-6 mb-0"><?= $totalGrupos ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h2 class="h6">Escalas</h2>
                <p class="display-6 mb-0"><?= $totalEscalas ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-dark">
            <div class="card-body">
                <h2 class="h6">Registros de ponto</h2>
                <p class="display-6 mb-0"><?= $totalRegistros ?></p>
            </div>
        </div>
    </div>
</div>
