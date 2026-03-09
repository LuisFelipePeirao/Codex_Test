<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/functions.php';

$page = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard', 'alunos', 'grupos', 'escalas', 'ponto'];

if (!in_array($page, $allowedPages, true)) {
    $page = 'dashboard';
}

$flash = getFlash();

require __DIR__ . '/includes/header.php';

if ($flash):
?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php
endif;

require __DIR__ . '/pages/' . $page . '.php';
require __DIR__ . '/includes/footer.php';
