<?php require_once __DIR__ . '/layout_back.php'; ?>

      <div class="back-section active" id="back-dashboard">
        <div class="back-header">
          <div>
            <h2>Dashboard</h2>
            <p>Vue d'ensemble de la plateforme — Avril 2026</p>
          </div>
          <div class="back-header-actions">
            <button class="btn btn-outline btn-sm" onclick="showToast('Rapport exporté !','success')">📥 Exporter</button>
            <button class="btn btn-primary btn-sm" onclick="showToast('Données actualisées !','success')">🔄 Actualiser</button>
          </div>
        </div>

        <div class="kpi-grid">
          <a href="index.php?page=users" style="text-decoration:none;">
            <div class="kpi-card">
              <div class="kpi-icon" style="background:linear-gradient(135deg,#6C3FC5,#9B5DE5)">👥</div>
              <div class="kpi-body">
                <div class="kpi-value"><?= number_format($stats['total'], 0, ',', ' ') ?></div>
                <div class="kpi-label">Utilisateurs totaux</div>
                <div class="kpi-trend up">↑ +8.2% ce mois</div>
              </div>
            </div>
          </a>
          <a href="index.php?page=profiles" style="text-decoration:none;">
            <div class="kpi-card">
              <div class="kpi-icon" style="background:linear-gradient(135deg,#00C2CB,#00a8b0)">🎨</div>
              <div class="kpi-body">
                <div class="kpi-value"><?= $stats['creators'] ?></div>
                <div class="kpi-label">Créateurs actifs</div>
                <div class="kpi-trend up">↑ +12.5% ce mois</div>
              </div>
            </div>
          </a>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:linear-gradient(135deg,#38a169,#2f855a)">✓</div>
            <div class="kpi-body">
              <div class="kpi-value"><?= $stats['verified'] ?></div>
              <div class="kpi-label">Comptes vérifiés</div>
              <div class="kpi-trend up">↑ +3.1% ce mois</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:linear-gradient(135deg,#e53e3e,#c53030)">🔔</div>
            <div class="kpi-body">
              <div class="kpi-value">7</div>
              <div class="kpi-label">Alertes sécurité</div>
              <div class="kpi-trend down">↓ -2 depuis hier</div>
            </div>
          </div>
        </div>

        <div class="charts-row">
          <div class="chart-card chart-large">
            <div class="chart-header">
              <h4>Inscriptions par mois</h4>
              <div class="chart-legend-inline">
                <span class="cli-dot" style="background:var(--secondary)"></span>2026
              </div>
            </div>
            <div class="bar-chart">
              <div class="bar-group"><div class="bar" style="--h:45%"><span class="bar-val">820</span></div><span class="bar-label">Jan</span></div>
              <div class="bar-group"><div class="bar" style="--h:60%"><span class="bar-val">1100</span></div><span class="bar-label">Fév</span></div>
              <div class="bar-group"><div class="bar" style="--h:52%"><span class="bar-val">950</span></div><span class="bar-label">Mar</span></div>
              <div class="bar-group"><div class="bar accent" style="--h:100%"><span class="bar-val">1847</span></div><span class="bar-label">Avr</span></div>
            </div>
          </div>
          <div class="chart-card">
            <h4>Répartition des rôles</h4>
            <div class="donut-chart-wrap">
              <svg class="donut-svg" viewBox="0 0 100 100" width="140" height="140">
                <circle cx="50" cy="50" r="38" fill="none" stroke="var(--bg2)" stroke-width="16"/>
                <circle cx="50" cy="50" r="38" fill="none" stroke="#6C3FC5" stroke-width="16" stroke-dasharray="138 101" stroke-dashoffset="0" />
                <circle cx="50" cy="50" r="38" fill="none" stroke="#00C2CB" stroke-width="16" stroke-dasharray="57 182" stroke-dashoffset="-138" />
                <circle cx="50" cy="50" r="38" fill="none" stroke="#9B5DE5" stroke-width="16" stroke-dasharray="33 206" stroke-dashoffset="-195" />
                <circle cx="50" cy="50" r="38" fill="none" stroke="#e53e3e" stroke-width="16" stroke-dasharray="10 229" stroke-dashoffset="-228" />
                <text x="50" y="46" text-anchor="middle" fill="var(--text)" font-size="10" font-weight="700">12K</text>
                <text x="50" y="58" text-anchor="middle" fill="var(--text3)" font-size="6">users</text>
              </svg>
              <div class="donut-legend">
                <div class="legend-item"><span class="legend-dot" style="background:#6C3FC5"></span><span>Créateur</span><strong>58%</strong></div>
                <div class="legend-item"><span class="legend-dot" style="background:#00C2CB"></span><span>Marque</span><strong>24%</strong></div>
                <div class="legend-item"><span class="legend-dot" style="background:#9B5DE5"></span><span>Creator Pro</span><strong>14%</strong></div>
                <div class="legend-item"><span class="legend-dot" style="background:#e53e3e"></span><span>Admin</span><strong>4%</strong></div>
              </div>
            </div>
          </div>
        </div>

        <div class="chart-card" style="margin-top:20px;">
          <h4>Activité récente</h4>
          <div class="activity-list">
            <?php foreach ($activities as $activity): ?>
            <div class="activity-item">
              <div class="activity-dot" style="background:<?= htmlspecialchars($activity['color']) ?>"></div>
              <div class="activity-text"><?= $activity['text'] ?></div>
              <div class="activity-time"><?= htmlspecialchars($activity['time']) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

<?php require_once __DIR__ . '/layout_back_end.php'; ?>
