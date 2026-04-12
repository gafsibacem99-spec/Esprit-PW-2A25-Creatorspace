<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="office active" id="front-office">
  <section class="front-section" id="profile-section">
    <div class="section-wrap">
      <a href="index.php?page=home"><button class="btn btn-outline btn-sm back-btn">← Retour</button></a>

      <div class="profile-header-card">
        <div class="profile-cover"><div class="profile-cover-overlay"></div></div>
        <div class="profile-header-body">
          <div class="profile-avatar-wrap">
            <div class="profile-avatar" style="background:linear-gradient(135deg,<?= htmlspecialchars($profile['color']) ?>,#9B5DE5)">
              <?= htmlspecialchars($profile['initials']) ?>
            </div>
            <div class="profile-verified" title="Compte vérifié">✓</div>
          </div>
          <div class="profile-info">
            <div class="profile-name-row">
              <h2><?= htmlspecialchars($profile['name']) ?></h2>
              <span class="badge badge-pro">⭐ <?= htmlspecialchars($profile['role']) ?></span>
              <span class="badge badge-verified">✓ Vérifié</span>
            </div>
            <p class="profile-handle">@sophie.creates</p>
            <p class="profile-bio">Créatrice de contenu lifestyle & beauté 🌸 | Partage mes aventures, astuces beauté et voyages.</p>
            <div class="profile-meta">
              <span>📍 Paris, France</span>
              <span>📅 Membre depuis <?= htmlspecialchars($profile['date']) ?></span>
              <span>✉️ <?= htmlspecialchars($profile['email']) ?></span>
            </div>
          </div>
          <div class="profile-actions">
            <button class="btn btn-primary btn-sm" onclick="handleFollow(this)">+ Suivre</button>
            <button class="btn btn-outline btn-sm" onclick="showToast('Message envoyé !','success')">💬 Message</button>
          </div>
        </div>
      </div>

      <div class="stats-row">
        <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-value"><?= htmlspecialchars($profile['followers']) ?></div><div class="stat-label">Abonnés</div></div>
        <div class="stat-card"><div class="stat-icon">👁️</div><div class="stat-value"><?= htmlspecialchars($profile['views']) ?></div><div class="stat-label">Vues totales</div></div>
        <div class="stat-card"><div class="stat-icon">📄</div><div class="stat-value"><?= $profile['content'] ?></div><div class="stat-label">Contenus</div></div>
        <div class="stat-card"><div class="stat-icon">💬</div><div class="stat-value">8.4%</div><div class="stat-label">Engagement</div></div>
      </div>

      <div class="profile-tabs">
        <button class="profile-tab active" onclick="switchProfileTab('info', this)">📋 Informations</button>
        <button class="profile-tab" onclick="switchProfileTab('social', this)">🔗 Liens sociaux</button>
        <button class="profile-tab" onclick="switchProfileTab('settings', this)">⚙️ Paramètres</button>
      </div>

      <!-- Tab: Informations -->
      <div class="profile-tab-content active" id="ptab-info">
        <div class="info-grid">
          <div class="info-card">
            <h4>À propos</h4>
            <p>Créatrice passionnée depuis 2019, Sophie partage son quotidien parisien, ses découvertes beauté et ses voyages.</p>
            <div class="info-tags">
              <span class="tag">🌸 Lifestyle</span><span class="tag">💄 Beauté</span><span class="tag">✈️ Voyage</span><span class="tag">👗 Mode</span>
            </div>
          </div>
          <div class="info-card">
            <h4>Audience par plateforme</h4>
            <div class="mini-stats">
              <div class="mini-stat-row"><span>YouTube</span><div class="mini-bar"><div style="width:78%"></div></div><span>78%</span></div>
              <div class="mini-stat-row"><span>Instagram</span><div class="mini-bar"><div style="width:92%"></div></div><span>92%</span></div>
              <div class="mini-stat-row"><span>TikTok</span><div class="mini-bar"><div style="width:65%"></div></div><span>65%</span></div>
              <div class="mini-stat-row"><span>LinkedIn</span><div class="mini-bar"><div style="width:40%"></div></div><span>40%</span></div>
            </div>
          </div>
          <div class="info-card">
            <h4>Derniers contenus</h4>
            <div class="content-list">
              <div class="content-item"><span class="content-thumb">▶</span><div><div class="content-title">Mon routine beauté matinale 🌅</div><div class="content-meta">YouTube · 142K vues · il y a 3j</div></div></div>
              <div class="content-item"><span class="content-thumb">📸</span><div><div class="content-title">Look été 2026 — Haul mode</div><div class="content-meta">Instagram · 89K likes · il y a 5j</div></div></div>
              <div class="content-item"><span class="content-thumb">🎵</span><div><div class="content-title">POV: Parisienne en vacances</div><div class="content-meta">TikTok · 1.2M vues · il y a 1sem</div></div></div>
            </div>
          </div>
          <div class="info-card">
            <h4>Collaborations récentes</h4>
            <div class="collab-list">
              <div class="collab-item"><div class="collab-brand">L'Oréal Paris</div><span class="badge badge-pro">Terminé</span></div>
              <div class="collab-item"><div class="collab-brand">Sephora France</div><span class="badge badge-verified">En cours</span></div>
              <div class="collab-item"><div class="collab-brand">Airbnb</div><span class="badge" style="background:rgba(237,137,54,0.15);color:#ed8936;">Négociation</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab: Social Links -->
      <div class="profile-tab-content" id="ptab-social">
        <div class="social-links-grid">
          <a href="#" class="social-link-card youtube" onclick="showToast('Ouverture YouTube…','info');return false;">
            <div class="slc-icon youtube-icon">▶</div>
            <div class="slc-info"><div class="social-name">YouTube</div><div class="social-handle">@SophieCreates</div></div>
            <div class="slc-stats"><div class="social-followers">142K</div><div class="slc-stat-label">abonnés</div></div>
          </a>
          <a href="#" class="social-link-card instagram" onclick="showToast('Ouverture Instagram…','info');return false;">
            <div class="slc-icon instagram-icon">📸</div>
            <div class="slc-info"><div class="social-name">Instagram</div><div class="social-handle">@sophie.creates</div></div>
            <div class="slc-stats"><div class="social-followers">89K</div><div class="slc-stat-label">abonnés</div></div>
          </a>
          <a href="#" class="social-link-card tiktok" onclick="showToast('Ouverture TikTok…','info');return false;">
            <div class="slc-icon tiktok-icon">🎵</div>
            <div class="slc-info"><div class="social-name">TikTok</div><div class="social-handle">@sophiecreates</div></div>
            <div class="slc-stats"><div class="social-followers">17K</div><div class="slc-stat-label">abonnés</div></div>
          </a>
          <a href="#" class="social-link-card linkedin" onclick="showToast('Ouverture LinkedIn…','info');return false;">
            <div class="slc-icon linkedin-icon">💼</div>
            <div class="slc-info"><div class="social-name">LinkedIn</div><div class="social-handle">Sophie Martin</div></div>
            <div class="slc-stats"><div class="social-followers">3.2K</div><div class="slc-stat-label">relations</div></div>
          </a>
        </div>
      </div>

      <!-- Tab: Settings -->
      <div class="profile-tab-content" id="ptab-settings">
        <div class="settings-grid">
          <div class="settings-card">
            <h4>✏️ Modifier le profil</h4>
            <form method="POST" action="index.php?page=profile">
              <div class="form-group"><label>Nom d'affichage</label><input type="text" name="display_name" value="<?= htmlspecialchars($profile['name']) ?>" /></div>
              <div class="form-group"><label>Bio</label><textarea name="bio" rows="3">Créatrice de contenu lifestyle & beauté 🌸</textarea></div>
              <div class="form-group"><label>Localisation</label><input type="text" name="location" value="Paris, France" /></div>
              <button type="submit" class="btn btn-primary btn-sm" onclick="showToast('Profil mis à jour !','success');return false;">Sauvegarder</button>
            </form>
          </div>
          <div class="settings-card">
            <h4>🔒 Sécurité</h4>
            <div class="form-group"><label>Mot de passe actuel</label><input type="password" placeholder="••••••••" /></div>
            <div class="form-group"><label>Nouveau mot de passe</label><input type="password" placeholder="••••••••" /></div>
            <div class="form-group"><label>Confirmer</label><input type="password" placeholder="••••••••" /></div>
            <button class="btn btn-primary btn-sm" onclick="showToast('Mot de passe modifié !','success')">Mettre à jour</button>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
