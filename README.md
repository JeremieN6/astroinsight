# 🌌 AstroInsights

**AstroInsights** est une application web innovante qui dépasse le simple horoscope quotidien.  
Elle propose une **analyse astrologique personnalisée**, basée sur le thème natal complet de l’utilisateur (date, heure, lieu de naissance), et enrichie d’outils interactifs pour mieux comprendre les cycles planétaires, les opportunités personnelles et les dynamiques relationnelles.  

🚀 Développée en **Symfony (backend)** et **Vue.js (frontend)**, l’application s’appuie sur des APIs astrologiques fiables pour générer des insights riches et engageants.

---

## ✨ Vision & Proposition de valeur

AstroInsights ambitionne de créer un **pont entre la tradition astrologique et la technologie moderne**.  
L’objectif est de rendre l’astrologie **interactive, visuelle et personnalisée**, au-delà des simples horoscopes généralistes.

- 🌠 **Différenciation** : là où la majorité des apps se limitent à un horoscope quotidien, AstroInsights propose des **analyses profondes**, des **visualisations claires** et des **rapports personnalisés**.  
- 👥 **Engagement communautaire** : en offrant compatibilité, comparaisons et conseils relationnels, l’app vise à fédérer une communauté active autour de l’astrologie.  
- 💡 **Valeur ajoutée** : insights psychologiques, cycles planétaires, biorythmes et notifications intelligentes aident l’utilisateur à prendre de meilleures décisions au quotidien.  

---

## ⚡ Fonctionnalités principales

- **Profil astrologique personnalisé**
  - Création de compte (e-mail ou réseaux sociaux).
  - Calcul automatique du thème natal à partir des infos de naissance.

- **Horoscope quotidien avancé**
  - Prédictions basées sur le signe solaire, lunaire, l’ascendant et les aspects planétaires.
  - Texte narratif enrichi et contextualisé.

- **Analyse des cycles planétaires**
  - Transits actuels et leurs impacts (amour, carrière, finances, bien-être).
  - Identification des phases favorables et défavorables.

- **Rapports personnalisés**
  - Rapports mensuels et annuels.
  - Événements astrologiques majeurs (éclipses, rétrogrades, nouvelles/pleines lunes).

- **Visualisations dynamiques**
  - Graphiques de biorythmes et cycles planétaires.
  - Indicateurs de jours favorables/défavorables.

- **Analyse de compatibilité**
  - Comparaison de thèmes astraux entre utilisateurs (ou avec célébrités).
  - Conseils relationnels personnalisés.

- **Insights psychologiques**
  - Analyse de personnalité basée sur le thème natal.
  - Suggestions pour le développement personnel.

- **Notifications intelligentes**
  - Alertes pour les événements astrologiques importants.
  - Insights quotidiens sur mobile ou desktop.

---

## 🛠️ Technologies utilisées

- **Backend :** [Symfony](https://symfony.com/) – API REST sécurisée, gestion des utilisateurs et logique métier.  
- **Frontend :** [Vue.js](https://vuejs.org/) – interface réactive, moderne et fluide.  
- **Base de données :** MySQL ou PostgreSQL.  
- **APIs externes :** intégration d’APIs astrologiques (Prokerala, FreeAstrologyAPI, AstroAPI.cloud…).  
- **Notifications push :** Web Push / Firebase Cloud Messaging.  

---

## 🚧 Roadmap (MVP → Version complète)

### Phase 1 : MVP
- [x] Authentification & gestion des utilisateurs.
- [x] Création du profil astrologique.
- [x] Horoscope quotidien personnalisé.

### Phase 2 : Analyse & visualisation
- [ ] Intégration des cycles planétaires & biorythmes.
- [ ] Rapports mensuels et annuels.

### Phase 3 : Compatibilité & insights
- [ ] Analyse de compatibilité entre utilisateurs.
- [ ] Insights psychologiques & conseils de développement personnel.

### Phase 4 : Notifications & optimisation
- [ ] Notifications push personnalisées.
- [ ] Optimisation des performances & corrections de bugs.

---

## ⚙️ Installation & lancement

### 1. Cloner le dépôt
```bash
git clone https://github.com/ton-compte/astroinsights.git
cd astroinsights
```

2. Installer les dépendances Symfony
```bash
Copier le code
composer install
```

3. Configurer l’environnement
Créer un fichier .env.local à partir de .env :

env
Copier le code
APP_ENV=dev
APP_SECRET=your_secret_key
DATABASE_URL="mysql://user:password@127.0.0.1:3306/astroinsights"
ASTRO_API_KEY=your_api_key_here

4. Créer la base de données & lancer les migrations
```bash
Copier le code
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Lancer le serveur Symfony
```bash
Copier le code
symfony serve
```

6. Lancer le frontend Vue.js
Dans le dossier /frontend :

```bash
Copier le code
npm install
npm run dev
```
Application accessible sur :
👉 http://localhost:8000

🤝 Contribution
Les contributions sont les bienvenues !

Fork le projet
Crée ta branche (git checkout -b feature/ma-fonctionnalite)
Commit tes changements (git commit -m 'Ajout nouvelle fonctionnalité')
Push (git push origin feature/ma-fonctionnalite)
Crée une Pull Request

📜 Licence
Projet distribué sous licence MIT.
Libre à toi de l’utiliser, le modifier et le partager.

🌠 Conclusion
AstroInsights offre une expérience riche et personnalisée, permettant aux utilisateurs d’aller au-delà des horoscopes quotidiens traditionnels.
En combinant précision des calculs astrologiques, analyses comportementales et visualisations modernes, l’application ambitionne de devenir la référence digitale de l’astrologie interactive.

👨‍💻 Développé avec ❤️ par @jeremiecode (contact: contact@jeremiecode.fr).
