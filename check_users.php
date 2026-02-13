<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n================== 🔐 VÉRIFICATION DES COMPTES ==================\n\n";

// Vérifier les comptes existants
$users = User::all();
echo "Comptes existants: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "  • " . $user->email . " (Role: " . $user->role . ")\n";
}

echo "\n================== ✅ RÉINITIALISER LES MOTS DE PASSE ==================\n\n";

// Réinitialiser les mots de passe
$password = 'password';
$hashedPassword = Hash::make($password);

$emails = ['admin@example.com', 'manager@example.com', 'observer@example.com'];
foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        $user->update(['password' => $hashedPassword]);
        echo "✅ " . $email . " - password mis à jour\n";
    }
}

echo "\n🔑 Tous les mots de passe sont: password\n";
echo "\n";
