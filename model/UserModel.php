<?php
/**
 * CreatorSpace — UserModel
 * REFACTOR: Pure data layer — no HTTP, no rendering, no session logic.
 * All filtering/pagination logic moved here from BackController (was a violation).
 */
class UserModel
{
    // FIX: Data stays in Model. In production, replace with PDO queries.
    private static array $users = [
        ['id'=>1,  'name'=>'Sophie Martin',  'initials'=>'SM', 'email'=>'sophie.martin@gmail.com',  'role'=>'Creator Pro', 'status'=>'active',   'date'=>'12 Jan 2022', 'color'=>'#6C3FC5', 'followers'=>'248K', 'views'=>'4.2M', 'content'=>312, 'completion'=>94],
        ['id'=>2,  'name'=>'Lucas Bernard',  'initials'=>'LB', 'email'=>'lucas.bernard@outlook.com', 'role'=>'Créateur',    'status'=>'active',   'date'=>'03 Mar 2022', 'color'=>'#00C2CB', 'followers'=>'89K',  'views'=>'1.1M', 'content'=>147, 'completion'=>78],
        ['id'=>3,  'name'=>'Emma Dubois',    'initials'=>'ED', 'email'=>'emma.dubois@yahoo.fr',       'role'=>'Marque',      'status'=>'active',   'date'=>'18 Jun 2022', 'color'=>'#9B5DE5', 'followers'=>'312K', 'views'=>'6.8M', 'content'=>428, 'completion'=>100],
        ['id'=>4,  'name'=>'Thomas Leroy',   'initials'=>'TL', 'email'=>'thomas.leroy@gmail.com',     'role'=>'Créateur',    'status'=>'inactive', 'date'=>'07 Sep 2022', 'color'=>'#e53e3e', 'followers'=>'22K',  'views'=>'310K', 'content'=>54,  'completion'=>45],
        ['id'=>5,  'name'=>'Chloé Moreau',   'initials'=>'CM', 'email'=>'chloe.moreau@gmail.com',     'role'=>'Creator Pro', 'status'=>'active',   'date'=>'22 Nov 2022', 'color'=>'#38a169', 'followers'=>'55K',  'views'=>'780K', 'content'=>93,  'completion'=>65],
        ['id'=>6,  'name'=>'Antoine Petit',  'initials'=>'AP', 'email'=>'antoine.petit@hotmail.com',  'role'=>'Créateur',    'status'=>'pending',  'date'=>'14 Jan 2023', 'color'=>'#ed8936', 'followers'=>'8K',   'views'=>'95K',  'content'=>21,  'completion'=>38],
        ['id'=>7,  'name'=>'Léa Rousseau',   'initials'=>'LR', 'email'=>'lea.rousseau@gmail.com',     'role'=>'Marque',      'status'=>'active',   'date'=>'05 Feb 2023', 'color'=>'#6C3FC5', 'followers'=>'127K', 'views'=>'2.3M', 'content'=>201, 'completion'=>88],
        ['id'=>8,  'name'=>'Hugo Garnier',   'initials'=>'HG', 'email'=>'hugo.garnier@gmail.com',     'role'=>'Créateur',    'status'=>'active',   'date'=>'19 Mar 2023', 'color'=>'#00C2CB', 'followers'=>'41K',  'views'=>'560K', 'content'=>88,  'completion'=>72],
        ['id'=>9,  'name'=>'Inès Faure',     'initials'=>'IF', 'email'=>'ines.faure@outlook.com',     'role'=>'Creator Pro', 'status'=>'active',   'date'=>'30 Apr 2023', 'color'=>'#9B5DE5', 'followers'=>'44K',  'views'=>'520K', 'content'=>67,  'completion'=>55],
        ['id'=>10, 'name'=>'Maxime Girard',  'initials'=>'MG', 'email'=>'maxime.girard@gmail.com',    'role'=>'Créateur',    'status'=>'inactive', 'date'=>'11 Jun 2023', 'color'=>'#e53e3e', 'followers'=>'5K',   'views'=>'48K',  'content'=>12,  'completion'=>22],
        ['id'=>11, 'name'=>'Camille Dupont', 'initials'=>'CD', 'email'=>'camille.dupont@gmail.com',   'role'=>'Marque',      'status'=>'active',   'date'=>'02 Jul 2023', 'color'=>'#38a169', 'followers'=>'198K', 'views'=>'3.1M', 'content'=>267, 'completion'=>91],
        ['id'=>12, 'name'=>'Romain Blanc',   'initials'=>'RB', 'email'=>'romain.blanc@outlook.com',   'role'=>'Créateur',    'status'=>'pending',  'date'=>'15 Aug 2023', 'color'=>'#ed8936', 'followers'=>'3K',   'views'=>'22K',  'content'=>8,   'completion'=>18],
    ];

    public function getAll(): array
    {
        return self::$users;
    }

    public function findById(int $id): ?array
    {
        foreach (self::$users as $user) {
            if ($user['id'] === $id) return $user;
        }
        return null;
    }

    public function findByEmail(string $email): ?array
    {
        foreach (self::$users as $user) {
            if ($user['email'] === $email) return $user;
        }
        return null;
    }

    /**
     * FIX: Filtering logic moved from BackController::users() to Model.
     * Controllers must not perform data filtering — that is Model responsibility.
     */
    public function search(string $query = '', string $role = '', string $status = ''): array
    {
        $users = self::$users;

        if ($query !== '') {
            $users = array_filter($users, fn($u) =>
                stripos($u['name'], $query)  !== false ||
                stripos($u['email'], $query) !== false ||
                stripos($u['role'], $query)  !== false
            );
        }
        if ($role !== '') {
            $users = array_filter($users, fn($u) => $u['role'] === $role);
        }
        if ($status !== '') {
            $users = array_filter($users, fn($u) => $u['status'] === $status);
        }

        return array_values($users);
    }

    /**
     * FIX: Pagination logic moved from BackController::users() to Model.
     */
    public function paginate(array $users, int $page, int $perPage = 8): array
    {
        $total      = count($users);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $offset     = ($page - 1) * $perPage;

        return [
            'items'       => array_slice($users, $offset, $perPage),
            'total'       => $total,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ];
    }

    /**
     * FIX: getCreators() now returns clean array — array_values() removed from BackController.
     */
    public function getCreators(): array
    {
        return array_values(
            array_filter(self::$users, fn($u) => $u['role'] !== 'Marque')
        );
    }

    public function getStats(): array
    {
        return [
            'total'    => count(self::$users),
            'active'   => count(array_filter(self::$users, fn($u) => $u['status'] === 'active')),
            'creators' => count(array_filter(self::$users, fn($u) => in_array($u['role'], ['Créateur', 'Creator Pro']))),
            'verified' => count(array_filter(self::$users, fn($u) => $u['completion'] >= 90)),
        ];
    }

    public function getActivities(): array
    {
        return [
            ['color'=>'#38a169', 'text'=>'<strong>Sophie Martin</strong> a publié un nouveau contenu',     'time'=>'Il y a 2 min'],
            ['color'=>'#6C3FC5', 'text'=>'<strong>Lucas Bernard</strong> a rejoint la plateforme',          'time'=>'Il y a 8 min'],
            ['color'=>'#00C2CB', 'text'=>'<strong>Emma Dubois</strong> a atteint 300K abonnés',             'time'=>'Il y a 15 min'],
            ['color'=>'#ed8936', 'text'=>'<strong>Antoine Petit</strong> est en attente de vérification',   'time'=>'Il y a 32 min'],
            ['color'=>'#e53e3e', 'text'=>'Alerte sécurité : tentative de connexion suspecte détectée',      'time'=>'Il y a 1h'],
            ['color'=>'#9B5DE5', 'text'=>'<strong>Chloé Moreau</strong> a signé un contrat avec L\'Oréal', 'time'=>'Il y a 2h'],
        ];
    }

    /**
     * FIX: Status label mapping moved from view/backoffice/users.php to Model.
     * Views must not contain data-mapping logic.
     */
    public static function statusLabel(string $status): string
    {
        return match($status) {
            'active'   => '● Actif',
            'inactive' => '● Inactif',
            'pending'  => '● En attente',
            default    => $status,
        };
    }

    /**
     * FIX: Status CSS class mapping moved from view/backoffice/users.php to Model.
     */
    public static function statusClass(string $status): string
    {
        return match($status) {
            'active'   => 'status-active',
            'inactive' => 'status-inactive',
            'pending'  => 'status-pending',
            default    => '',
        };
    }

    /**
     * FIX: Role definitions moved from view/backoffice/roles.php to Model.
     * Business data must never be hardcoded in views.
     */
    public function getRoles(): array
    {
        return [
            [
                'name'  => 'Créateur',
                'icon'  => '🎨',
                'count' => '7,432',
                'color' => 'linear-gradient(135deg,#6C3FC5,#9B5DE5)',
                'badge' => 'Gratuit',
                'badge_class' => 'badge-pro',
                'perms' => [
                    ['label' => 'Créer du contenu',        'enabled' => true],
                    ['label' => 'Publier des posts',        'enabled' => true],
                    ['label' => 'Accès analytics basique',  'enabled' => true],
                    ['label' => 'Monétisation',             'enabled' => false],
                    ['label' => 'API access',               'enabled' => false],
                    ['label' => 'Gestion d\'équipe',        'enabled' => false],
                ],
            ],
            [
                'name'  => 'Creator Pro',
                'icon'  => '⭐',
                'count' => '1,803',
                'color' => 'linear-gradient(135deg,#00C2CB,#00a8b0)',
                'badge' => 'Premium',
                'badge_class' => 'badge-verified',
                'perms' => [
                    ['label' => 'Créer du contenu',   'enabled' => true],
                    ['label' => 'Publier des posts',   'enabled' => true],
                    ['label' => 'Analytics avancés',   'enabled' => true],
                    ['label' => 'Monétisation',        'enabled' => true],
                    ['label' => 'API access',          'enabled' => true],
                    ['label' => 'Gestion d\'équipe',   'enabled' => false],
                ],
            ],
            [
                'name'  => 'Marque',
                'icon'  => '🏢',
                'count' => '3,082',
                'color' => 'linear-gradient(135deg,#ed8936,#dd6b20)',
                'badge' => 'Business',
                'badge_class' => 'badge-business',
                'perms' => [
                    ['label' => 'Créer du contenu',   'enabled' => true],
                    ['label' => 'Publier des posts',   'enabled' => true],
                    ['label' => 'Analytics avancés',   'enabled' => true],
                    ['label' => 'Monétisation',        'enabled' => true],
                    ['label' => 'API access',          'enabled' => true],
                    ['label' => 'Gestion d\'équipe',   'enabled' => true],
                ],
            ],
        ];
    }
}
