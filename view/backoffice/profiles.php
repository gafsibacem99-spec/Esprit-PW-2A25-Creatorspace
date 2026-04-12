<?php require_once __DIR__ . '/layout_back.php'; ?>

      <div class="back-section active" id="back-profiles">
        <div class="back-header">
          <div><h2>Profils créateurs</h2><p>Statistiques et taux de complétion</p></div>
          <button class="btn btn-outline btn-sm" onclick="showToast('Export CSV en cours…','info')">📥 Exporter</button>
        </div>
        <div class="profiles-grid">
          <?php foreach ($creators as $p): ?>
          <div class="profile-admin-card">
            <div class="pac-header">
              <div class="pac-avatar" style="background:<?= htmlspecialchars($p['color']) ?>"><?= htmlspecialchars($p['initials']) ?></div>
              <div>
                <div class="pac-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="pac-handle"><?= htmlspecialchars($p['email']) ?></div>
              </div>
              <span class="role-badge" style="margin-left:auto;"><?= htmlspecialchars($p['role']) ?></span>
            </div>
            <div class="pac-stats">
              <div class="pac-stat"><div class="pac-stat-val"><?= htmlspecialchars($p['followers']) ?></div><div class="pac-stat-lbl">Abonnés</div></div>
              <div class="pac-stat"><div class="pac-stat-val"><?= htmlspecialchars($p['views']) ?></div><div class="pac-stat-lbl">Vues</div></div>
              <div class="pac-stat"><div class="pac-stat-val"><?= $p['content'] ?></div><div class="pac-stat-lbl">Contenus</div></div>
            </div>
            <div class="completion-label"><span>Complétion du profil</span><span><?= $p['completion'] ?>%</span></div>
            <div class="completion-bar"><div class="completion-fill" style="width:<?= $p['completion'] ?>%"></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

<?php require_once __DIR__ . '/layout_back_end.php'; ?>
