<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [v-cloak] { display: none; }
    </style>
</head>
<body class="bg-gray-50">
    <div id="app" v-cloak>
        <!-- HEADER -->
        <header class="bg-blue-600 text-white p-6 shadow-lg">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold">📦 Stock Manager</h1>
                <p class="text-blue-100 mt-2">Système intelligent de gestion des stocks</p>
            </div>
        </header>

        <!-- NAVIGATION -->
        <nav v-if="isAuthenticated" class="bg-white shadow border-b-4 border-blue-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex space-x-8">
                        <a href="#" @click.prevent="currentPage = 'dashboard'" :class="currentPage === 'dashboard' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">📊 Dashboard</a>
                        <a href="#" @click.prevent="currentPage = 'products'" :class="currentPage === 'products' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">📦 Produits</a>
                        <a href="#" @click.prevent="currentPage = 'movements'" :class="currentPage === 'movements' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">➕ Mouvements</a>
                        <a href="#" @click.prevent="currentPage = 'inventory'" :class="currentPage === 'inventory' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">📝 Inventaire</a>
                        <a href="#" @click.prevent="currentPage = 'alerts'" :class="currentPage === 'alerts' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">⚠️ Alertes</a>
                        <a href="#" @click.prevent="currentPage = 'predictions'" :class="currentPage === 'predictions' ? 'border-b-4 border-blue-600 font-bold text-gray-900' : 'text-gray-700'" class="hover:text-blue-600 py-4">🔮 Prédictions</a>
                    </div>
                    <button @click="logout()" class="bg-red-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-600">Déconnexion</button>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- MESSAGES -->
            <div v-if="message" :class="error ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 'bg-green-100 border-l-4 border-green-500 text-green-700'" class="p-4 mb-4 rounded">
                @{{ message }}
            </div>

            <!-- ===== LOGIN PAGE ===== -->
            <transition name="fade" mode="out-in">
            <template v-if="!isAuthenticated && !showRegister">
                <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                    <div class="max-w-md w-full mx-auto">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 border-t-4 border-blue-600">
                            <div class="text-center mb-8">
                                <div class="text-5xl mb-3">📦</div>
                                <h2 class="text-3xl font-bold text-gray-800">Stock Manager</h2>
                                <p class="text-gray-500 mt-2">Connectez-vous à votre compte</p>
                            </div>
                            <form @submit.prevent="login()" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">📧 Email</label>
                                    <input v-model="loginForm.email" type="email" placeholder="admin@example.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔐 Mot de passe</label>
                                    <input v-model="loginForm.password" type="password" placeholder="••••••••" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required>
                                </div>
                                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Se Connecter
                                </button>
                            </form>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <p class="text-center text-gray-600">
                                    Pas de compte? <a href="#" @click.prevent="showRegister = true" class="text-blue-600 font-bold hover:text-blue-700 underline">S'inscrire</a>
                                </p>
                            </div>
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg text-sm text-gray-700">
                                <p class="font-semibold mb-2">🔍 Comptes de test :</p>
                                <p>• admin@example.com</p>
                                <p>• password: <strong>password</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            </transition>

            <!-- ===== REGISTER PAGE ===== -->
            <transition name="fade" mode="out-in">
            <template v-if="!isAuthenticated && showRegister">
                <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100">
                    <div class="max-w-md w-full mx-auto">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 border-t-4 border-green-600">
                            <div class="text-center mb-8">
                                <div class="text-5xl mb-3">📝</div>
                                <h2 class="text-3xl font-bold text-gray-800">Créer un compte</h2>
                                <p class="text-gray-500 mt-2">Rejoignez Stock Manager</p>
                            </div>
                            <div class="mb-4 text-right">
                                <button type="button" @click="showRegister = false" class="text-sm text-gray-600 hover:underline">← Retour à la connexion</button>
                            </div>
                            <form @submit.prevent="register()" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Nom</label>
                                    <input v-model="registerForm.name" type="text" placeholder="Votre nom" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">📧 Email</label>
                                    <input v-model="registerForm.email" type="email" placeholder="votre@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔐 Mot de passe</label>
                                    <input v-model="registerForm.password" type="password" placeholder="••••••••" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition" required>
                                </div>
                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold py-3 rounded-lg hover:from-green-700 hover:to-emerald-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    S'inscrire
                                </button>
                            </form>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <p class="text-center text-gray-600">
                                    Déjà inscrit? <a href="#" @click.prevent="showRegister = false" class="text-green-600 font-bold hover:text-green-700 underline">Se connecter</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            </transition>

            <!-- ===== DASHBOARD PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'dashboard'">
                <h2 class="text-4xl font-bold mb-8">Dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                        <p class="text-sm opacity-90">Total Produits</p>
                        <p class="text-4xl font-bold">@{{ stats.total_products || 0 }}</p>
                    </div>
                    <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                        <p class="text-sm opacity-90">Valeur Stock</p>
                        <p class="text-3xl font-bold">$@{{ (stats.total_stock_value || 0).toFixed(2) }}</p>
                    </div>
                    <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg">
                        <p class="text-sm opacity-90">Rupture Stock</p>
                        <p class="text-4xl font-bold">@{{ stats.low_stock_products || 0 }}</p>
                    </div>
                    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
                        <p class="text-sm opacity-90">Alertes</p>
                        <p class="text-4xl font-bold">@{{ stats.pending_alerts || 0 }}</p>
                    </div>
                </div>

                <!-- PRODUITS SUR LE DASHBOARD -->
                <div class="mt-12">
                    <h3 class="text-3xl font-bold mb-6">📦 Nos Produits</h3>
                    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Produit</th>
                                    <th class="px-6 py-4 text-left font-bold">Catégorie</th>
                                    <th class="px-6 py-4 text-left font-bold">Prix</th>
                                    <th class="px-6 py-4 text-left font-bold">Stock Actuel</th>
                                    <th class="px-6 py-4 text-left font-bold">Min / Optimal</th>
                                    <th class="px-6 py-4 text-left font-bold">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.id" class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-800">@{{ product.name }}</td>
                                    <td class="px-6 py-4 text-gray-700">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">@{{ product.category?.name || 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-green-600">$@{{ product.price }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="product.current_quantity <= product.min_stock ? 'bg-red-100 text-red-800 font-bold' : 'bg-green-100 text-green-800'" class="px-3 py-1 rounded-full">@{{ product.current_quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">@{{ product.min_stock }} / @{{ product.optimal_stock }}</td>
                                    <td class="px-6 py-4">
                                        <span v-if="product.current_quantity > product.optimal_stock" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">✅ Optimal</span>
                                        <span v-else-if="product.current_quantity > product.min_stock" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">⚠️ Normal</span>
                                        <span v-else class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">🔴 Critique</span>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun produit disponible</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            <!-- ===== PRODUCTS PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'products'">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-4xl font-bold">Produits</h2>
                    <button @click="showProductForm = !showProductForm" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">+ Ajouter</button>
                </div>

                <div v-if="showProductForm" class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <h3 class="text-2xl font-bold mb-4">Nouveau Produit</h3>
                    <form @submit.prevent="saveProduct()" class="grid grid-cols-2 gap-4">
                        <input v-model="newProduct.name" type="text" placeholder="Nom" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <select v-model="newProduct.category_id" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                            <option value="">Catégorie</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                        </select>
                        <input v-model="newProduct.price" type="number" step="0.01" placeholder="Prix" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <input v-model="newProduct.current_quantity" type="number" placeholder="Stock" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <input v-model="newProduct.min_stock" type="number" placeholder="Min Stock" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <input v-model="newProduct.optimal_stock" type="number" placeholder="Optimal" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                        <button type="submit" class="col-span-2 bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700">Enregistrer</button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold">Nom</th>
                                <th class="px-6 py-3 text-left font-bold">Catégorie</th>
                                <th class="px-6 py-3 text-left font-bold">Stock</th>
                                <th class="px-6 py-3 text-left font-bold">Prix</th>
                                <th class="px-6 py-3 text-left font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id" class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">@{{ product.name }}</td>
                                <td class="px-6 py-3">@{{ product.category?.name || 'N/A' }}</td>
                                <td class="px-6 py-3" :class="product.current_quantity <= product.min_stock ? 'text-red-600 font-bold' : ''">@{{ product.current_quantity }} / @{{ product.optimal_stock }}</td>
                                <td class="px-6 py-3">$@{{ product.price }}</td>
                                <td class="px-6 py-3">
                                    <button @click="deleteProduct(product.id)" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <!-- ===== MOVEMENTS PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'movements'">
                <h2 class="text-4xl font-bold mb-8">Mouvements de Stock</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-4">Enregistrer Mouvement</h3>
                    <form @submit.prevent="recordMovement()" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <select v-model="newMovement.product_id" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                                <option value="">Produit</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">@{{ product.name }}</option>
                            </select>
                            <select v-model="newMovement.type" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                                <option value="entry">Entrée</option>
                                <option value="exit">Sortie</option>
                            </select>
                            <input v-model="newMovement.quantity" type="number" min="1" placeholder="Quantité" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                            <input v-model="newMovement.notes" type="text" placeholder="Notes" class="px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500">
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700">Enregistrer</button>
                    </form>
                </div>
            </template>

            <!-- ===== INVENTORY PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'inventory'">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-4xl font-bold">📝 Gestion des Inventaires</h2>
                    <button @click="createInventory()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-bold hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">+ Nouvel Inventaire</button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- LISTE DES INVENTAIRES -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                                <h3 class="font-bold text-lg">📋 Inventaires</h3>
                                <p class="text-blue-100 text-sm">Total: @{{ inventories.length }}</p>
                            </div>
                            <div class="divide-y max-h-96 overflow-y-auto">
                                <div v-if="inventories.length === 0" class="p-6 text-center text-gray-500">
                                    Aucun inventaire
                                </div>
                                <div v-for="inventory in inventories" 
                                     :key="inventory.id"
                                     @click="selectedInventory = inventory"
                                     :class="selectedInventory?.id === inventory.id ? 'bg-blue-50 border-l-4 border-blue-600' : 'hover:bg-gray-50 border-l-4 border-transparent'"
                                     class="p-4 cursor-pointer transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-gray-800">Inventaire #@{{ inventory.id }}</p>
                                            <p class="text-sm text-gray-600">📅 @{{ new Date(inventory.created_at).toLocaleDateString('fr-FR') }}</p>
                                            <p class="text-sm text-gray-600">⏰ @{{ new Date(inventory.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'}) }}</p>
                                        </div>
                                        <span :class="{
                                            'bg-green-100 text-green-800': inventory.status === 'completed',
                                            'bg-yellow-100 text-yellow-800': inventory.status === 'in_progress',
                                            'bg-blue-100 text-blue-800': inventory.status === 'pending',
                                            'bg-gray-100 text-gray-800': inventory.status === 'archived'
                                        }" class="px-2 py-1 rounded text-xs font-bold whitespace-nowrap">
                                            @{{ inventory.status === 'completed' ? '✅ Complété' : (inventory.status === 'in_progress' ? '⏳ En cours' : (inventory.status === 'pending' ? '⏳ En attente' : '📦 Archivé')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DÉTAILS DE L'INVENTAIRE -->
                    <div class="lg:col-span-2">
                        <div v-if="!selectedInventory" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl shadow-lg p-12 text-center">
                            <p class="text-5xl mb-4">📦</p>
                            <p class="text-gray-600 text-lg">Sélectionnez un inventaire pour voir les détails</p>
                        </div>

                        <div v-if="selectedInventory" class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <!-- HEADER -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold">Inventaire #@{{ selectedInventory.id }}</h3>
                                        <p class="text-blue-100 mt-1">📅 @{{ new Date(selectedInventory.created_at).toLocaleDateString('fr-FR', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}) }}</p>
                                    </div>
                                    <span :class="{
                                        'bg-green-400': selectedInventory.status === 'completed',
                                        'bg-yellow-400': selectedInventory.status === 'in_progress',
                                        'bg-blue-400': selectedInventory.status === 'pending',
                                        'bg-gray-400': selectedInventory.status === 'archived'
                                    }" class="px-4 py-2 rounded-lg text-white font-bold">
                                        @{{ selectedInventory.status === 'completed' ? '✅ Complété' : (selectedInventory.status === 'in_progress' ? '⏳ En cours' : (selectedInventory.status === 'pending' ? '⏳ En attente' : '📦 Archivé')) }}
                                    </span>
                                </div>
                            </div>

                            <!-- CONTENU -->
                            <div class="p-6">
                                <!-- STATISTIQUES -->
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
                                        <p class="text-sm text-gray-600">Articles</p>
                                        <p class="text-2xl font-bold text-blue-600">@{{ selectedInventory.items?.length || 0 }}</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                                        <p class="text-sm text-gray-600">Complété</p>
                                        <p class="text-2xl font-bold text-green-600">@{{ selectedInventory.status === 'completed' ? '✅' : '⏳' }}</p>
                                    </div>
                                    <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-600">
                                        <p class="text-sm text-gray-600">Variance</p>
                                        <p class="text-2xl font-bold text-purple-600">-</p>
                                    </div>
                                </div>

                                <!-- TABLEAU DES ARTICLES -->
                                <h4 class="font-bold text-lg mb-4">Détail des Articles</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-100 border-b-2 border-gray-300">
                                                <th class="px-4 py-3 text-left font-bold">Produit</th>
                                                <th class="px-4 py-3 text-center font-bold">Système</th>
                                                <th class="px-4 py-3 text-center font-bold">Compté</th>
                                                <th class="px-4 py-3 text-center font-bold">Variance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in selectedInventory.items" :key="item.id" class="border-b hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 font-semibold">@{{ item.product?.name || 'N/A' }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">@{{ item.system_quantity }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">@{{ item.counted_quantity }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span :class="item.variance > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'" class="px-3 py-1 rounded-full font-bold">
                                                        @{{ item.variance > 0 ? '+' : '' }}@{{ item.variance }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="!selectedInventory.items || selectedInventory.items.length === 0">
                                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun article dans cet inventaire</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- JUSTIFICATION -->
                                <div v-if="selectedInventory.items && selectedInventory.items.length > 0" class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="font-bold mb-2">📝 Observations</h4>
                                    <p class="text-gray-700">@{{ selectedInventory.items[0]?.justification || 'Aucune observation' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ===== ALERTS PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'alerts'">
                <h2 class="text-4xl font-bold mb-8">Alertes</h2>
                <div class="space-y-4">
                    <div v-if="alerts.length === 0" class="bg-blue-100 text-blue-800 p-6 rounded-lg text-center">
                        Aucune alerte
                    </div>
                    <div v-for="alert in alerts" :key="alert.id" :class="{
                        'bg-red-50 border-l-4 border-red-500': alert.severity === 'critical',
                        'bg-orange-50 border-l-4 border-orange-500': alert.severity === 'high',
                        'bg-yellow-50 border-l-4 border-yellow-500': alert.severity === 'medium',
                        'bg-blue-50 border-l-4 border-blue-500': alert.severity === 'low'
                    }" class="p-4 rounded-lg">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-bold">@{{ alert.product?.name }}</p>
                                <p class="text-gray-700">@{{ alert.message }}</p>
                            </div>
                            <span :class="{
                                'bg-red-200 text-red-800': alert.severity === 'critical',
                                'bg-orange-200 text-orange-800': alert.severity === 'high',
                                'bg-yellow-200 text-yellow-800': alert.severity === 'medium',
                                'bg-blue-200 text-blue-800': alert.severity === 'low'
                            }" class="px-3 py-1 rounded font-bold">@{{ alert.severity }}</span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ===== PREDICTIONS PAGE ===== -->
            <template v-if="isAuthenticated && currentPage === 'predictions'">
                <h2 class="text-4xl font-bold mb-8">Prédictions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-4">Générer</h3>
                        <form @submit.prevent="generatePredictions()" class="space-y-4">
                            <select v-model="predictionProduct" class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                                <option value="">Produit</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">@{{ product.name }}</option>
                            </select>
                            <select v-model="predictionMethod" class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                                <option value="linear_regression">Régression Linéaire</option>
                                <option value="moving_average">Moyenne Mobile</option>
                                <option value="ml_light">ML Léger</option>
                            </select>
                            <input v-model="predictionDays" type="number" min="1" max="90" placeholder="Jours" class="w-full px-4 py-2 border-2 border-gray-300 rounded focus:outline-none focus:border-blue-500" required>
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700">Générer</button>
                        </form>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-4">Résultats</h3>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            <div v-if="predictions.length === 0" class="text-gray-500 text-center py-8">Aucune prédiction</div>
                            <div v-for="pred in predictions" :key="pred.id" class="bg-gray-50 p-3 rounded border-l-4 border-blue-500">
                                <p class="font-bold">@{{ pred.product?.name }}</p>
                                <p class="text-sm">@{{ pred.method }}: @{{ pred.predicted_quantity }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </main>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    isAuthenticated: false,
                    token: localStorage.getItem('token') || null,
                    currentPage: 'dashboard',
                    showRegister: false,
                    showProductForm: false,
                    loginForm: { email: '', password: '' },
                    registerForm: { name: '', email: '', password: '' },
                    newProduct: { name: '', category_id: '', price: 0, current_quantity: 0, min_stock: 0, optimal_stock: 0 },
                    newMovement: { product_id: '', type: 'entry', quantity: 0, notes: '' },
                    predictionProduct: '',
                    predictionMethod: 'linear_regression',
                    predictionDays: 7,
                    stats: {},
                    products: [],
                    categories: [],
                    alerts: [],
                    inventories: [],
                    predictions: [],
                    message: '',
                    error: false
                }
            },
            methods: {
                async login() {
                    try {
                        const res = await axios.post('/api/v1/auth/login', this.loginForm);
                        this.token = res.data.data.token;
                        localStorage.setItem('token', this.token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                        this.isAuthenticated = true;
                        this.message = 'Connexion réussie!';
                        this.error = false;
                        this.loadData();
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                async register() {
                    try {
                        await axios.post('/api/v1/auth/register', this.registerForm);
                        this.message = 'Inscription réussie! Connectez-vous.';
                        this.error = false;
                        this.showRegister = false;
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                async loadData() {
                    try {
                        const headers = { 'Authorization': `Bearer ${this.token}` };
                        const [dashRes, prodRes, catRes, alertRes, invRes, predRes] = await Promise.all([
                            axios.get('/api/v1/dashboard', { headers }),
                            axios.get('/api/v1/products', { headers }),
                            axios.get('/api/v1/categories', { headers }),
                            axios.get('/api/v1/alerts', { headers }),
                            axios.get('/api/v1/inventories', { headers }),
                            axios.get('/api/v1/predictions', { headers })
                        ]);
                        this.stats = dashRes.data.data;
                        this.products = prodRes.data.data.data || [];
                        this.categories = catRes.data.data.data || [];
                        this.alerts = alertRes.data.data.data || [];
                        this.inventories = invRes.data.data.data || [];
                        this.predictions = predRes.data.data.data || [];
                    } catch (err) {
                        console.error('Erreur chargement:', err);
                    }
                },
                async saveProduct() {
                    try {
                        await axios.post('/api/v1/products', this.newProduct, { headers: { 'Authorization': `Bearer ${this.token}` } });
                        this.message = 'Produit créé!';
                        this.error = false;
                        this.newProduct = { name: '', category_id: '', price: 0, current_quantity: 0, min_stock: 0, optimal_stock: 0 };
                        this.showProductForm = false;
                        await this.loadData();
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                async deleteProduct(id) {
                    if (confirm('Êtes-vous sûr?')) {
                        try {
                            await axios.delete(`/api/v1/products/${id}`, { headers: { 'Authorization': `Bearer ${this.token}` } });
                            this.message = 'Produit supprimé!';
                            this.error = false;
                            await this.loadData();
                        } catch (err) {
                            this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                            this.error = true;
                        }
                    }
                },
                async recordMovement() {
                    try {
                        await axios.post('/api/v1/stock-movements', this.newMovement, { headers: { 'Authorization': `Bearer ${this.token}` } });
                        this.message = 'Mouvement enregistré!';
                        this.error = false;
                        this.newMovement = { product_id: '', type: 'entry', quantity: 0, notes: '' };
                        await this.loadData();
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                async createInventory() {
                    try {
                        const payload = { date: new Date().toISOString(), description: '' };
                        await axios.post('/api/v1/inventories', payload, { headers: { 'Authorization': `Bearer ${this.token}` } });
                        this.message = 'Inventaire créé!';
                        this.error = false;
                        await this.loadData();
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                async generatePredictions() {
                    try {
                        await axios.post('/api/v1/predictions', { product_id: this.predictionProduct, method: this.predictionMethod, days: this.predictionDays }, { headers: { 'Authorization': `Bearer ${this.token}` } });
                        this.message = 'Prédiction générée!';
                        this.error = false;
                        await this.loadData();
                    } catch (err) {
                        this.message = 'Erreur: ' + (err.response?.data?.message || err.message);
                        this.error = true;
                    }
                },
                logout() {
                    this.isAuthenticated = false;
                    this.token = null;
                    localStorage.removeItem('token');
                    this.message = 'Déconnecté';
                    this.error = false;
                }
            },
            mounted() {
                if (this.token) {
                    axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                    this.isAuthenticated = true;
                    this.loadData();
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
