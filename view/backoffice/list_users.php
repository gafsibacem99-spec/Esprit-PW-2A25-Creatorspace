<?php
/**
 * list_users.php — Backoffice: full user list with CRUD actions.
 * Uses UserModel::statusLabel() and UserModel::statusClass() — no logic in view.
 */
require_once __DIR__ . '/layout_back.php';
?>

      <div class="back-section active" id="back-users">
        <div class="back-header">
          <div><h2>Utilisateurs</h2><p>Gérez tous les comptes de la plateforme</p></div>
          <a href="index.php?page=user&action=create">
            <button class="btn btn-primary btn-sm">+ Ajouter un utilisateur</button>
          </a>
        </div>

        <?php if ($success): ?>
        <div style="background:#e0ffe0;border:1px solid green;color:green;padding:10px 15px;margin-bottom:16px;border-radius:5px;">
          ✅ Opération effectuée avec succès.
        </div>
        <?php endif; ?>

        <div class="table-card">
          <div class="table-toolbar">
            <form method="GET" action="index.php" style="display:contents;">
              <input type="hidden" name="page" value="user" />
              <input type="hidden" name="action" value="list" />
              <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search"
                       placeholder="Rechercher par nom, email, rôle…"
                       value="<?= htmlspecialchars($search) ?>" />
              </div>
              <div class="toolbar-filters">
                <select name="role" onchange="this.form.submit()">
                  <option value="">Tous les rôles</option>
                  <?php foreach (['Creator Pro', 'Créateur', 'Marque'] as $r): ?>
                  <option value="<?= htmlspecialchars($r) ?>"
                          <?= $roleFilter === $r ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <select name="status" onchange="this.form.submit()">
                  <option value="">Tous les statuts</option>
                  <option value="active"   <?= $statusFilter === 'active'   ? 'selected' : '' ?>>Actif</option>
                  <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                  <option value="pending"  <?= $statusFilter === 'pending'  ? 'selected' : '' ?>>En attente</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">🔍 Filtrer</button>
              </div>
            </form>
          </div>

          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Utilisateur</th><th>Email</th><th>Rôle</th>
                  <th>Statut</th><th>Inscrit le</th><th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-mini-avatar" style="background:<?= htmlspecialchars($u['color']) ?>">
                        <?= htmlspecialchars($u['initials']) ?>
                      </div>
                      <span class="user-name"><?= htmlspecialchars($u['name']) ?></span>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td><span class="role-badge"><?= htmlspecialchars($u['role']) ?></span></td>
                  <td>
                    <span class="status-badge <?= UserModel::statusClass($u['status']) ?>">
                      <?= UserModel::statusLabel($u['status']) ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($u['date']) ?></td>
                  <td>
                    <div class="table-actions">
                      <a href="index.php?page=user&action=edit&id=<?= (int)$u['id'] ?>">
                        <button class="action-btn" title="Modifier">✏️</button>
                      </a>
                      <a href="index.php?page=user&action=delete&id=<?= (int)$u['id'] ?>"
                         onclick="return confirm('Supprimer cet utilisateur ?');">
                        <button class="action-btn del" title="Supprimer">🗑</button>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            <span><?= $total ?> utilisateur<?= $total > 1 ? 's' : '' ?></span>
            <div class="pagination">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="index.php?page=user&action=list&p=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&status=<?= urlencode($statusFilter) ?>">
                <button class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></button>
              </a>
              <?php endfor; ?>
            </div>
          </div>
        </div>
      </div>

<?php require_once __DIR__ . '/layout_back_end.php'; ?>
