<?php
session_start();
require __DIR__ . '/db.php';

$user = $_SESSION['user'] ?? null;
if (!$user) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($prenom === '' || $nom === '' || $email === '') {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo = getPdo();
            $stmt = $pdo->prepare('UPDATE users SET prenom = ?, nom = ?, email = ? WHERE id = ?');
            $stmt->execute([$prenom, $nom, $email, $user['id']]);
            $_SESSION['user']['prenom'] = $prenom;
            $_SESSION['user']['nom'] = $nom;
            $_SESSION['user']['email'] = $email;
            $success = 'Informations du compte mises à jour.';
        } catch (PDOException $e) {
            $error = 'Impossible de mettre à jour le compte.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer le compte</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #0f172a; }
        .page { display: flex; min-height: 100vh; }
        .main { flex: 1; padding: 40px 48px; }
        .card { max-width: 560px; background: #fff; border-radius: 20px; box-shadow: 0 20px 50px rgba(15,23,42,0.08); padding: 32px; }
        h1 { margin-top: 0; margin-bottom: 8px; font-size: 32px; }
        p { margin-top: 0; margin-bottom: 24px; color: #64748b; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 8px; font-weight: 600; }
        .field input { width: 100%; padding: 14px 16px; border: 1px solid #d8e1ef; border-radius: 14px; font-size: 15px; }
        .btn { padding: 14px 18px; border: none; border-radius: 14px; background: #0a276d; color: #fff; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #081f5b; }
        .message { margin-bottom: 18px; padding: 14px 16px; border-radius: 12px; }
        .message.success { background: #d1fae5; color: #065f46; }
        .message.error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <?php $activeMenu = 'dashboard'; include __DIR__ . '/sidebar.php'; ?>
    <main class="main">
        <div class="card">
            <h1>Gérer le compte</h1>
            <p>Modifiez vos informations personnelles et votre email de connexion.</p>
            <?php if ($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post" action="account.php">
                <div class="field">
                    <label for="prenom">Prénom</label>
                    <input id="prenom" name="prenom" type="text" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                </div>
                <div class="field">
                    <label for="nom">Nom</label>
                    <input id="nom" name="nom" type="text" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" class="btn">Enregistrer les modifications</button>
            </form>
        </div>
    </main>
</body>
</html>
