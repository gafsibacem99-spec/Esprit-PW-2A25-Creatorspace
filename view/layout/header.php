<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CreatorSpace — Crée. Publie. Monétise.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/variables.css" />
  <link rel="stylesheet" href="assets/css/base.css" />
  <link rel="stylesheet" href="assets/css/components.css" />
  <link rel="stylesheet" href="assets/css/front.css" />
  <link rel="stylesheet" href="assets/css/back.css" />
  <link rel="stylesheet" href="assets/css/responsive.css" />
</head>
<body>

<?php
/**
 * FIX: Flash message read via SessionManager — not raw $_SESSION access in view.
 * Views must not access superglobals directly.
 */
$flash = SessionManager::getFlash();

/**
 * FIX: Active-state logic for nav moved from inline ternaries to PHP variables.
 * Views should receive ready-to-use booleans, not compute logic inline.
 */
$isFront = in_array($page ?? '', ['home', 'profile'], true);
$isBack  = in_array($page ?? '', ['dashboard', 'users', 'profiles', 'roles', 'settings'], true);
?>

<nav class="topnav" id="topnav">
  <div class="topnav-logo" onclick="window.location='index.php?page=home'">✦ CreatorSpace</div>
  <div class="topnav-switcher">
    <a href="index.php?page=home">
      <button class="<?= $isFront ? 'active' : '' ?>">🌐 Front Office</button>
    </a>
    <a href="index.php?page=dashboard">
      <button class="<?= $isBack ? 'active' : '' ?>">⚙️ Back Office</button>
    </a>
  </div>
  <div class="topnav-right">
    <?php if (!empty($currentUser)): ?>
      <div class="topnav-user">
        <div class="topnav-avatar" style="background:<?= htmlspecialchars($currentUser['color']) ?>">
          <?= htmlspecialchars($currentUser['initials']) ?>
        </div>
        <span><?= htmlspecialchars($currentUser['name']) ?></span>
      </div>
      <a href="index.php?page=logout">
        <button class="btn btn-sm btn-outline">Déconnexion</button>
      </a>
    <?php endif; ?>
    <button class="theme-toggle" onclick="toggleTheme()" id="themeBtn" title="Changer le thème">🌙</button>
  </div>
</nav>

<?php if ($flash): ?>
<div id="flash-message" class="toast <?= htmlspecialchars($flash['type']) ?>"
     style="position:fixed;top:80px;right:24px;z-index:3000;display:flex;">
  <span class="toast-msg"><?= htmlspecialchars($flash['message']) ?></span>
  <span class="toast-close" onclick="this.parentElement.remove()">✕</span>
</div>
<script>setTimeout(() => { const f = document.getElementById('flash-message'); if (f) f.remove(); }, 4000);</script>
<?php endif; ?>
