<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\Alert;

echo "\n================== 📊 DONNÉES DE LA BASE ==================\n\n";
echo "✅ Users: " . User::count() . " compte(s)\n";
echo "✅ Products: " . Product::count() . " produit(s)\n";
echo "✅ Categories: " . Category::count() . " catégorie(s)\n";
echo "✅ Inventories: " . Inventory::count() . " inventaire(s)\n";
echo "✅ Stock Movements: " . StockMovement::count() . " mouvement(s)\n";
echo "✅ Alerts: " . Alert::count() . " alerte(s)\n";

echo "\n================== 🔐 COMPTES DE TEST ==================\n\n";
$users = User::all();
foreach ($users as $user) {
    echo "  • " . $user->email . " (Role: " . $user->role . ")\n";
}

echo "\n--> Mot de passe pour tous les comptes: password\n";

echo "\n================== 📦 PRODUITS DISPONIBLES ==================\n\n";
foreach (Product::with('category')->get() as $product) {
    echo "  • " . $product->name . " (Catégorie: " . $product->category?->name . ")\n";
}

echo "\n✅ Tous les tableaux sont créés et remplis!\n\n";

