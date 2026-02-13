# Système de Gestion de Stock Intelligent avec Prédiction

Un système complet de gestion de stock utilisant Laravel et Vue.js avec des fonctionnalités avancées de prédiction et d'alertes intelligentes.

## Fonctionnalités Principales

### 1. **Gestion des Produits et Catégories**
- Création, modification, suppression de produits
- Catégorisation hiérarchique (catégories parent/enfant)
- Suivi des niveaux de stock minimum et optimal
- Code-barres unique par produit
- Information fournisseur et prix

### 2. **Mouvements de Stock**
- Enregistrement des entrées (achats, retours, corrections)
- Enregistrement des sorties (ventes, pertes, casse, expiration)
- Historique complète traçable par produit, date et type
- Traçabilité utilisateur pour chaque mouvement

### 3. **Inventaire Physique**
- Réalisation d'inventaires avec saisie des quantités
- Ajustement automatique en cas d'écart
- Justification des variances
- Archivage pour traçabilité historique

### 4. **Module de Prédiction Intelligent**
- **Techniques utilisées:**
  - Régression linéaire
  - Moyenne mobile (7 jours)
  - Machine Learning léger (si > 100 enregistrements)
  
- **Prédictions disponibles:**
  - 7 jours, 30 jours, 3 mois
  - Calcul du risque de rupture
  - Recommandations d'achat

### 5. **Système d'Alertes**
- Alerte stock minimum atteint
- Alerte risque de rupture
- Alerte surstock
- Alerte expiration proche
- Niveaux de sévérité (low, medium, high, critical)

### 6. **Tableau de Bord**
- Statistiques en temps réel
- Graphiques interactifs
- Taux de rotation
- Tendances sur 30 jours

### 7. **Exports**
- PDF et Excel/CSV
- Fiches produit automatiques
- Données tableau de bord

### 8. **Gestion des Utilisateurs**
- **Admin:** Accès complet
- **Gestionnaire:** Produits + mouvements
- **Observateur:** Lecture seule
- Authentification JWT

---

## Installation Rapide

```bash
# 1. Cloner le repository
git clone <repository-url>
cd nuptia

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env

# 5. Exécuter les migrations
php artisan migrate
php artisan db:seed

# 6. Compiler les assets
npm run build

# 7. Démarrer
php artisan serve
```

Accédez à: `http://localhost:8000`

## Comptes de Test

- Email: `admin@example.com` | Password: `password` (Admin)
- Email: `manager@example.com` | Password: `password` (Gestionnaire)
- Email: `observer@example.com` | Password: `password` (Observateur)

---

## Documentation

- [API Documentation](API_DOCUMENTATION.md)
- [Cahier des charges](CAHIER_DES_CHARGES.md)

---

## Stack Technique

- Laravel 12 + PHP 8.2
- Vue.js 3
- MySQL/PostgreSQL
- Tailwind CSS
- Axios

---

## License

MIT License - 2026
