# Design System: PFPT CFPT Platform

## Vibe & Atmosphere
- **Style:** Professional, Institutionnel, Clair, Moderne.
- **Audience:** Administrateurs, formateurs (Sénégal/Japon), et étudiants africains.
- **Thème:** Application Web / Tableau de bord analytique et administratif.

## Design Tokens

### Typography
- **Primary Font:** Inter ou Roboto (Polices système propres idéales pour la lisibilité de données).

### Color Palette
- **Primary:** Royal Blue (`#0F4C81`) - Représente l'institution, le sérieux (inspiré JICA/CFPT).
- **Secondary / Accent:** Vert Saphir ou Orange doux (pour les Call-To-Action (CTA) ou validation).
- **Background:** Gris TRES clair (`#F8FAFC`) pour faire ressortir les cartes (cards) blanches.
- **Surface:** Blanc (`#FFFFFF`) pour les conteneurs (formulaires, tableaux).
- **Text:** Ardoise foncé (`#1E293B`) pour les titres, gris moyen (`#64748B`) pour le texte léger.

### Shapes & Components
- **Roundness:** `Round-Eight` (Coins légèrement arrondis pour un aspect moderne mais structuré).
- **Shadows:** Élévation légère sur les cartes (card) pour séparer les sections. Glassmorphism subtil autorisé uniquement sur les menus de navigation latéraux.
- **Cards:** Utilisées intensivement pour les données statistiques (Nombre de candidats, etc.).

## Page Structures

### 1. Interface d'Authentification (Login)
- Centenaire de la page : Formulaire de connexion épuré encadré dans une carte blanche.
- Panneau latéral : Une image/illustration représentant la formation ou la carte de l'Afrique avec un design circulaire (Sénégal/Japon) évoqué dans le mémoire.

### 2. Tableau de Bord Admin (Dashboard)
- **Sidebar (Menu latéral):** Liens vers Candidats, Formations, Finances/Per Diem, Archives.
- **Header:** Profil utilisateur, notifications, et filtre global par "Année d'édition".
- **Main Content:**
  - Ligne 1 : "Quick Stats" (Candidats inscrits, Pays participants, Formateurs engagés).
  - Ligne 2 : "Charts" (Répartition des domaines de formation en graphique circulaire).
  - Ligne 3 : Tableau récent ou Liste d'actions rapides.
