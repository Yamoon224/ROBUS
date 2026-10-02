# ROBUS Manager : rapport d'audit et d'optimisation

**Date :** 2 octobre 2026 · **Périmètre :** application web manager.robus.africa · **Destinataire :** Direction générale

## En bref

L'application répond plus vite, ne s'interrompt plus pendant les mises à jour et affiche un tableau de bord et un menu plus lisibles. Trois lots de corrections sont prêts mais pas encore en ligne (marqués « à publier »). Le contrôle automatique des boutons a relevé 78 anomalies : 4 sont corrigées, 74 restent à trier par l'équipe technique.

## Ce qui a été fait

| Domaine | Modification | Statut |
|---|---|---|
| Disponibilité | Mises à jour sans coupure : le site reste accessible pendant le déploiement | En ligne |
| Fiabilité | Chaque mise à jour est vérifiée (étape « Build ») avant de toucher au serveur | En ligne |
| Base de données | Index de recherche ajoutés sur les tables de transactions | En ligne |
| Base de données | Lectures groupées : une requête par liste au lieu d'une par ligne | En ligne |
| Tableau de bord | Réorganisé : soldes, statuts par service, montants ; 3 chiffres erronés corrigés | En ligne |
| Menu | Deux fois plus de liens visibles, menu fixe au défilement, version mobile allégée | En ligne |
| Boutons | « Je prends en charge » réparé sur la page des transactions | En ligne |
| Affichage | Marges roses réduites autour des pages | À publier |
| Vitesse | Fichiers gardés en mémoire par le navigateur ; fichier principal chargé une seule fois | À publier |
| Boutons | 4 liens « Facture » corrigés (Push, Djamo, Trésor Money, envoi bancaire) | À publier |

## Gains de temps

| Mesure | Avant | Après | Nature |
|---|---|---|---|
| Coupure du site à chaque mise à jour | 30 à 37 s | 0 s | Mesuré |
| Page des transactions : requêtes à la base | jusqu'à 500 | environ 40 | Calculé |
| Page des transactions : temps passé en base | environ 1,5 s | environ 0,1 s | Estimé |
| Page des transactions : premier chargement | fichier de 8,4 Mo lu 2 fois | lu 1 fois, environ 3,5 s de moins | Mesuré, à publier |
| Pages suivantes : fichiers revérifiés | environ 15 par page, 0,7 à 1 s chacun | 0 | Mesuré, à publier |
| Liens visibles dans le menu sans défiler | 8 | 17 | Mesuré |

**Lecture des chiffres.** « Mesuré » : relevé depuis notre poste sur le site en production, ou sur une maquette pour le menu. « Calculé » : compté dans le code (12 listes de 15 lignes, 2 à 3 lectures par ligne). « Estimé » : calcul fondé sur une hypothèse de 3 ms par requête ; aucune mesure n'a été possible sur les pages protégées par mot de passe.

## Contrôle des boutons

Le contrôle est automatique et porte sur le code, sans clic réel dans l'application : 658 actions de boutons sur 236 écrans, et 477 liens internes.

- 31 boutons appellent une action qui n'existe pas.
- 47 liens pointent vers une page qui n'existe pas. Les 4 qui pouvaient bloquer la liste des transactions sont corrigés.
- La liste complète figure dans l'annexe technique `docs/audit_annexe_technique.md`.

## Bonnes pratiques appliquées

- Une seule fonction par besoin partagé (liens de fichiers, recherche de compte, cadre de page) au lieu de copies dans chaque écran.
- Les changements d'apparence sont isolés dans des fichiers dédiés, sans toucher aux règles métier ni aux circuits d'argent.
- Chaque modification est tracée dans l'historique du code et réversible.

## Limites et suites recommandées

1. **Publier les trois lots en attente**, puis vérifier l'affichage des pages et la page des transactions.
2. **Trier les 74 anomalies restantes** : certaines concernent des écrans qui ne sont plus utilisés.
3. **Mesurer les temps réels** des pages connectées avec un compte de test, pour remplacer les estimations.
4. **Alléger le fichier principal de 8,4 Mo** et le compteur du menu (12 requêtes par page) : non traités, car ils demandent des tests avec un compte.
