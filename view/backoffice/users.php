<?php
/**
 * FIX: Status label/class mapping removed from this view.
 * Now uses UserModel::statusLabel() and UserModel::statusClass() — data logic belongs in Model.
 */
require_once __DIR__ . '/layout_back.php';
?>

      <div class="back-section active" id="back-users">
        <div class="back-header">
          <div><h2>Utilisateurs</h2><p>Gérez tous les comptes de la plateforme</p></div>
          <button class="btn btn-primary btn-sm" onclick="openModal('add-user-modal')">+ Ajouter un utilisateur</button>
        </div>

        <div class="table-card">
          <div class="table-toolbar">
            <form method="GET" action="index.php" style="display:contents;">
              <input type="hidden" name="page" value="users" />
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
                  <th><input type="checkbox" /></th>
                  <th>Utilisateur</th><th>Email</th><th>Rôle</th>
                  <th>Statut</th><th>Inscrit le</th><th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                  <td><input type="checkbox" style="width:auto;" /></td>
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
                    <?php
                    /* FIX: Status mapping delegated to UserModel static helpers — not computed in view. */
                    $sClass = UserModel::statusClass($u['status']);
                    $sLabel = UserModel::statusLabel($u['status']);
                    ?>
                    <span class="status-badge <?= $sClass ?>"><?= $sLabel ?></span>
                  </td>
                  <td><?= htmlspecialchars($u['date']) ?></td>
                  <td>
                    <div class="table-actions">
                      <button class="action-btn" title="Voir" onclick="viewUser(<?= (int)$u['id'] ?>)">👁</button>
                      <button class="action-btn" title="Modifier" onclick="showToast('Modification…','info')">✏️</button>
                      <button class="action-btn del" title="Supprimer" onclick="confirmDelete(<?= (int)$u['id'] ?>)">🗑</button>
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
              <a href="index.php?page=users&p=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>&status=<?= urlencode($statusFilter) ?>">
                <button class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></button>
              </a>
              <?php endfor; ?>
            </div>
          </div>
        </div>
      </div>

<!-- Add User Modal -->
<div class="modal" id="add-user-modal">
  <div class="modal-header">
    <h3>👤 Ajouter un utilisateur</h3>
    <button class="modal-close" onclick="closeAllModals()">✕</button>
  </div>
  <div class="modal-body">
    <div class="form-row-2">
      <div class="form-group"><label>Prénom</label><input type="text" placeholder="Jean" /></div>
      <div class="form-group"><label>Nom</label><input type="text" placeholder="Dupont" /></div>
    </div>
    <div class="form-group"><label>Email</label><input type="email" placeholder="jean@exemple.com" /></div>
    <div class="form-group"><label>Rôle</label>
      <select><option>Créateur</option><option>Creator Pro</option><option>Marque</option></select>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-outline btn-sm" onclick="closeAllModals()">Annuler</button>
    <button class="btn btn-primary btn-sm" onclick="closeAllModals();showToast('Utilisateur ajouté !','success')">✅ Créer</button>
  </div>
</div>

<!-- View User Modal -->
<div class="modal" id="view-user-modal">
  <div class="modal-header">
    <h3>👁️ Détails utilisateur</h3>
    <button class="modal-close" onclick="closeAllModals()">✕</button>
  </div>
  <div class="modal-body" id="view-user-body"></div>
  <div class="modal-footer">
    <button class="btn btn-outline btn-sm" onclick="closeAllModals()">Fermer</button>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal" id="confirm-delete-modal">
  <div class="modal-header">
    <h3>🗑️ Confirmer la suppression</h3>
    <button class="modal-close" onclick="closeAllModals()">✕</button>
  </div>
  <div class="modal-body">
    <p style="color:var(--text2);">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-outline btn-sm" onclick="closeAllModals()">Annuler</button>
    <button class="btn btn-danger btn-sm" id="confirm-delete-btn">🗑️ Supprimer</button>
  </div>
</div>

<?php require_once __DIR__ . '/layout_back_end.php'; ?>
