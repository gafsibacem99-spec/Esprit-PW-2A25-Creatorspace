<?php
/**
 * add_user.php — Backoffice: create a new user.
 * NO HTML5 validation attributes (no required, no pattern, no type="email").
 * Validation: JS (view/js/validate.js) + PHP (UserController::validateForm).
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="office active" id="back-office">
  <div class="back-layout">

    <?php /* Minimal inline sidebar for standalone page */ ?>
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="sidebar-logo">✦ CreatorSpace</div>
        <div class="sidebar-subtitle">Admin Panel</div>
      </div>
      <nav class="sidebar-nav">
        <a href="index.php?page=dashboard">
          <button class="sidebar-item">📊 Dashboard</button>
        </a>
        <a href="index.php?page=user&action=list">
          <button class="sidebar-item active">👥 Utilisateurs</button>
        </a>
      </nav>
    </aside>

    <main class="back-main">
      <div class="back-header">
        <div>
          <h2>Ajouter un utilisateur</h2>
          <p>Remplissez tous les champs pour créer un nouveau compte.</p>
        </div>
        <a href="index.php?page=user&action=list">
          <button class="btn btn-outline btn-sm">← Retour à la liste</button>
        </a>
      </div>

      <?php /* PHP server-side errors — displayed when JS is disabled or bypassed */ ?>
      <?php if (!empty($errors)): ?>
      <div style="background:#ffe0e0;border:1px solid red;color:red;padding:10px 15px;margin-bottom:15px;border-radius:5px;font-size:14px;">
        <ul style="margin:0;padding-left:18px;">
          <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php /* onsubmit calls validate.js — return false blocks submission on error */ ?>
      <form id="user-form" method="POST" action="index.php?page=user&action=create"
            onsubmit="return validateForm();"
            style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:32px;max-width:560px;box-shadow:var(--shadow);">

        <div class="form-row-2">
          <div class="form-group">
            <label for="nom">Nom</label>
            <?php /* NO required, NO pattern, NO type="email" — validation is JS + PHP only */ ?>
            <input type="text" id="nom" name="nom"
                   placeholder="Martin"
                   value="<?= htmlspecialchars($data['nom'] ?? '') ?>" />
          </div>
          <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom"
                   placeholder="Sophie"
                   value="<?= htmlspecialchars($data['prenom'] ?? '') ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <?php /* type="text" — NOT type="email" per constraint */ ?>
          <input type="text" id="email" name="email"
                 placeholder="exemple@gmail.com"
                 value="<?= htmlspecialchars($data['email'] ?? '') ?>" />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password"
                 placeholder="Minimum 6 caractères" />
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role">
            <option value="Créateur"    <?= ($data['role'] ?? '') === 'Créateur'    ? 'selected' : '' ?>>Créateur</option>
            <option value="Creator Pro" <?= ($data['role'] ?? '') === 'Creator Pro' ? 'selected' : '' ?>>Creator Pro</option>
            <option value="Marque"      <?= ($data['role'] ?? '') === 'Marque'      ? 'selected' : '' ?>>Marque</option>
          </select>
        </div>

        <div class="form-group">
          <label for="status">Statut</label>
          <select id="status" name="status">
            <option value="active"   <?= ($data['status'] ?? '') === 'active'   ? 'selected' : '' ?>>Actif</option>
            <option value="pending"  <?= ($data['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>En attente</option>
            <option value="inactive" <?= ($data['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
          </select>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
          <button type="submit" class="btn btn-primary btn-sm">✅ Créer l'utilisateur</button>
          <a href="index.php?page=user&action=list">
            <button type="button" class="btn btn-outline btn-sm">Annuler</button>
          </a>
        </div>
      </form>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
<?php /* validate.js loaded AFTER the form — provides onsubmit + live border feedback */ ?>
<script src="view/js/validate.js"></script>
