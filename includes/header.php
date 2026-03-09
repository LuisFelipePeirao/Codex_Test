<?php
$activePage = $_GET['page'] ?? 'dashboard';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Ponto Acadêmico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Ponto Acadêmico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'alunos' ? 'active' : '' ?>" href="index.php?page=alunos">Alunos</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'grupos' ? 'active' : '' ?>" href="index.php?page=grupos">Grupos</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'escalas' ? 'active' : '' ?>" href="index.php?page=escalas">Escalas</a></li>
                <li class="nav-item"><a class="nav-link <?= $activePage === 'ponto' ? 'active' : '' ?>" href="index.php?page=ponto">Registro de Ponto</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container pb-5">
