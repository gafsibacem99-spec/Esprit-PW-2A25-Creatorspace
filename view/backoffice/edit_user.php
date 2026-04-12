<?php
/**
 * edit_user.php — Backoffice: edit an existing user.
 * NO HTML5 validation attributes (no required, no pattern, no type="email").
 * Validation: JS (view/js/validate.js) + PHP (UserController::validateForm).
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="office active" id="back-office">
  <div class="back-layout">

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
          <h2>Modifier l'utilisateur</h2>
          <p>Modifiez les informations du compte.</p>
        </div>
        <a href="index.php?page=user&action=list">
          <button class="btn btn-outline btn-sm">← Retour à la liste</button>
        </a>
      </div>

      <?php /* PHP server-side errors */ ?>
      <?php if (!empty($errors)): ?>
      <div style="background:#ffe0e0;border:1px solid red;color:red;padding:10px 15px;margin-bottom:15px;border-radius:5px;font-size:14px;">
        <ul style="margin:0;padding-left:18px;">
          <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php
      /* Pre-split name into nom/prenom for the form fields */
      $nameParts = explode(' ', $user['name'] ?? '', 2);
      $nomVal    = $nameParts[0] ?? '';
      $prenomVal = $nameParts[1] ?? '';
      ?>

      <form id="user-form"
            method="POST"
            action="index.php?page=user&action=edit&id=<?= (int)$user['id'] ?>"
            onsubmit="return validateForm();"
            style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:32px;max-width:560px;box-shadow:var(--shadow);">

        <div class="form-row-2">
          <div class="form-group">
            <label for="nom">Nom</label>
            <?php /* NO required, NO pattern, NO type="email" */ ?>
            <input type="text" id="nom" name="nom"
                   placeholder="Martin"
                   value="<?= htmlspecialchars($nomVal) ?>" />
          </div>
          <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom"
                   placeholder="Sophie"
                   value="<?= htmlspecialchars($prenomVal) ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <?php /* type="text" — NOT type="email" per constraint */ ?>
          <input type="text" id="email" name="email"
                 placeholder="exemple@gmail.com"
                 value="<?= htmlspecialchars($user['email'] ?? '') ?>" />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password"
                 placeholder="Laisser vide pour ne pas changer" />
          <span class="text-muted">Minimum 6 caractères si renseigné.</span>
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role">
            <option value="Créateur"    <?= ($user['role'] ?? '') === 'Créateur'    ? 'selected' : '' ?>>Créateur</option>
            <option value="Creator Pro" <?= ($user['role'] ?? '') === 'Creator Pro' ? 'selected' : '' ?>>Creator Pro</option>
            <option value="Marque"      <?= ($user['role'] ?? '') === 'Marque'      ? 'selected' : '' ?>>Marque</option>
          </select>
        </div>

        <div class="form-group">
          <label for="status">Statut</label>
          <select id="status" name="status">
            <option value="active"   <?= ($user['status'] ?? '') === 'active'   ? 'selected' : '' ?>>Actif</option>
            <option value="pending"  <?= ($user['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>En attente</option>
            <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
          </select>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
          <button type="submit" class="btn btn-primary btn-sm">💾 Sauvegarder</button>
          <a href="index.php?page=user&action=list">
            <button type="button" class="btn btn-outline btn-sm">Annuler</button>
          </a>
        </div>
      </form>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
<script src="view/js/validate.js"></script>
