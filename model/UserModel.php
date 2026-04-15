<?php
// model/UserModel.php — pure data layer, no HTML, no echo

class UserModel {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom, mail, role FROM user");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByMail(string $mail): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE mail = ? LIMIT 1");
        $stmt->execute([$mail]);
        return $stmt->fetch();
    }

    public function insert(array $data): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO `user` (nom, prenom, mail, `password`, role, type_compte)
             VALUES (?, ?, ?, MD5(?), 'user', ?)"
        );
        $stmt->execute([
            trim($data['nom']),
            trim($data['prenom']),
            trim($data['mail']),
            trim($data['password']),
            trim($data['type_compte'] ?? 'user')
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            "UPDATE `user` SET nom=?, prenom=?, mail=?, role=?, type_compte=? WHERE id=?"
        );
        $stmt->execute([
            trim($data['nom']),
            trim($data['prenom']),
            trim($data['mail']),
            $data['role']        ?? 'user',
            $data['type_compte'] ?? 'user',
            $id
        ]);
    }

    public function updateProfile(int $id, array $data): void {
        if (!empty(trim($data['password'] ?? ''))) {
            $stmt = $this->pdo->prepare(
                "UPDATE `user` SET nom=?, prenom=?, mail=?, `password`=MD5(?), type_compte=? WHERE id=?"
            );
            $stmt->execute([
                trim($data['nom']),
                trim($data['prenom']),
                trim($data['mail']),
                trim($data['password']),
                trim($data['type_compte'] ?? 'user'),
                $id
            ]);
        } else {
            $stmt = $this->pdo->prepare(
                "UPDATE `user` SET nom=?, prenom=?, mail=?, type_compte=? WHERE id=?"
            );
            $stmt->execute([
                trim($data['nom']),
                trim($data['prenom']),
                trim($data['mail']),
                trim($data['type_compte'] ?? 'user'),
                $id
            ]);
        }
    }

    // mailExiste() — vérifie unicité email, exclut l'id courant en édition
    public function mailExiste(string $mail, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM user WHERE mail = ? AND id != ?"
        );
        $stmt->execute([$mail, $excludeId]);
        return $stmt->fetch() !== false;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function countAll(): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `user`");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function countByRole(string $role): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `user` WHERE `role` = ?");
        $stmt->execute([$role]);
        return (int)$stmt->fetchColumn();
    }

    public function countByType(string $type): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `user` WHERE `type_compte` = ?");
        $stmt->execute([$type]);
        return (int)$stmt->fetchColumn();
    }

    public function countNewThisMonth(): int {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT COUNT(*) FROM `user`
                 WHERE MONTH(created_at) = MONTH(NOW())
                 AND YEAR(created_at) = YEAR(NOW())"
            );
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    public function getLastFive(): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, nom, prenom, mail, role, type_compte
             FROM `user` ORDER BY id DESC LIMIT 5"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Helpers for BackController (original CreatorSpace) ────

    public function findById(int $id): array|false {
        return $this->getById($id);
    }

    public function getStats(): array {
        $all = $this->getAll();
        return [
            'total'    => count($all),
            'active'   => count($all),
            'creators' => count(array_filter($all, fn($u) => $u['role'] !== 'admin')),
            'verified' => count(array_filter($all, fn($u) => $u['role'] === 'admin')),
        ];
    }

    public function getActivities(): array {
        return [
            ['color'=>'#38a169', 'text'=>'<strong>Mohamed Marzougui</strong> s\'est connecté', 'time'=>'Il y a 2 min'],
            ['color'=>'#6C3FC5', 'text'=>'Un nouvel utilisateur a rejoint la plateforme',       'time'=>'Il y a 8 min'],
            ['color'=>'#00C2CB', 'text'=>'Mise à jour du système effectuée',                    'time'=>'Il y a 15 min'],
        ];
    }

    public function getCreators(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE role != 'admin'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRoles(): array {
        return [
            ['name'=>'admin','icon'=>'🔐','count'=>'1','color'=>'linear-gradient(135deg,#6C3FC5,#9B5DE5)',
             'badge'=>'Admin','badge_class'=>'badge-pro',
             'perms'=>[['label'=>'Accès complet','enabled'=>true],['label'=>'Gestion utilisateurs','enabled'=>true]]],
            ['name'=>'user', 'icon'=>'👤','count'=>'4','color'=>'linear-gradient(135deg,#00C2CB,#00a8b0)',
             'badge'=>'User', 'badge_class'=>'badge-verified',
             'perms'=>[['label'=>'Accès profil','enabled'=>true],['label'=>'Gestion utilisateurs','enabled'=>false]]],
        ];
    }

    public function search(string $query = '', string $role = '', string $status = ''): array {
        $all = $this->getAll();
        if ($query !== '') {
            $all = array_filter($all, fn($u) =>
                stripos($u['nom'],  $query) !== false ||
                stripos($u['mail'], $query) !== false ||
                stripos($u['role'], $query) !== false
            );
        }
        if ($role !== '') {
            $all = array_filter($all, fn($u) => $u['role'] === $role);
        }
        return array_values($all);
    }

    public function paginate(array $users, int $page, int $perPage = 8): array {
        $total = count($users);
        return [
            'items'       => array_slice($users, ($page - 1) * $perPage, $perPage),
            'total'       => $total,
            'currentPage' => $page,
            'totalPages'  => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public static function statusLabel(string $status): string {
        return match($status) {
            'active'   => '● Actif',
            'inactive' => '● Inactif',
            'pending'  => '● En attente',
            default    => $status,
        };
    }

    public static function statusClass(string $status): string {
        return match($status) {
            'active'   => 'status-active',
            'inactive' => 'status-inactive',
            'pending'  => 'status-pending',
            default    => '',
        };
    }
}
