<?php

namespace Database\Seeders;

use App\Models\Sid\SidUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
      {
          DB::table('sid_role_has_permissions')->delete();
          DB::table('sid_model_has_roles')->delete();
          DB::table('sid_model_has_permissions')->delete();
          DB::table('sid_roles')->delete();
          DB::table('sid_permissions')->delete();
          DB::table('sid_features')->delete();

          $data = array (
              0 =>
              array (
                'name' => 'configurations',
                'description' => 'Menu configuration',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              1 =>
              array (
                'name' => 'fonctionnalites',
                'description' => 'Menu fonctionnalité',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              2 =>
              array (
                'name' => 'utilisateurs',
                'description' => 'Menu utilisateur',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              3 =>
              array (
                'name' => 'permissions',
                'description' => 'Menu permissions',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              4 =>
              array (
                'name' => 'roles',
                'description' => 'Menu rôle',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              5 =>
              array (
                'name' => 'tools',
                'description' => 'tools',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              6 =>
              array (
                'name' => 'administrateurs',
                'description' => 'Administrateurs',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              7 =>
              array (
                'name' => 'entreprises',
                'description' => 'Menu entreprise',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              8 =>
              array (
                'name' => 'categorie',
                'description' => 'Menu categorie',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              9 =>
              array (
                'name' => 'formule',
                'description' => 'Menu formule',
                'guard_name' => 'web',
                'feature_id' => 1,
                'feature_name' => 'menu',
                'feature_description' => 'Afficher le menu',
              ),
              10 =>
              array (
                'name' => 'voir-fonctionnalite',
                'description' => 'Menu fonctionnalité',
                'guard_name' => 'web',
                'feature_id' => 2,
                'feature_name' => 'Gestion des fonctionnalités',
                'feature_description' => 'Gestion des fonctionnalités',
              ),
              11 =>
              array (
                'name' => 'modifier-fonctionnalite',
                'description' => 'Modifier une fonctionnalité',
                'guard_name' => 'web',
                'feature_id' => 2,
                'feature_name' => 'Gestion des fonctionnalités',
                'feature_description' => 'Gestion des fonctionnalités',
              ),
              12 =>
              array (
                'name' => 'supprimer-fonctionnalite',
                'description' => 'Supprimer une fonctionnalité',
                'guard_name' => 'web',
                'feature_id' => 2,
                'feature_name' => 'Gestion des fonctionnalités',
                'feature_description' => 'Gestion des fonctionnalités',
              ),
              13 =>
              array (
                'name' => 'ajouter-fonctionnalite',
                'description' => 'Ajouter une fonctionnalité',
                'guard_name' => 'web',
                'feature_id' => 2,
                'feature_name' => 'Gestion des fonctionnalités',
                'feature_description' => 'Gestion des fonctionnalités',
              ),
              14 =>
              array (
                'name' => 'voir-permission',
                'description' => 'Voir les permissions',
                'guard_name' => 'web',
                'feature_id' => 3,
                'feature_name' => 'Gestion des permissions',
                'feature_description' => 'Gestion des permissions',
              ),
              15 =>
              array (
                'name' => 'ajouter-permission',
                'description' => 'Ajouter une permission',
                'guard_name' => 'web',
                'feature_id' => 3,
                'feature_name' => 'Gestion des permissions',
                'feature_description' => 'Gestion des permissions',
              ),
              16 =>
              array (
                'name' => 'modifier-permission',
                'description' => 'Modifier une permission',
                'guard_name' => 'web',
                'feature_id' => 3,
                'feature_name' => 'Gestion des permissions',
                'feature_description' => 'Gestion des permissions',
              ),
              17 =>
              array (
                'name' => 'supprimer-permission',
                'description' => 'Supprimer une permission',
                'guard_name' => 'web',
                'feature_id' => 3,
                'feature_name' => 'Gestion des permissions',
                'feature_description' => 'Gestion des permissions',
              ),
              18 =>
              array (
                'name' => 'voir-role',
                'description' => 'Voir les rôles',
                'guard_name' => 'web',
                'feature_id' => 4,
                'feature_name' => 'Gestion des rôles',
                'feature_description' => 'Gestion des rôles',
              ),
              19 =>
              array (
                'name' => 'ajouter-role',
                'description' => 'Ajouter un rôle',
                'guard_name' => 'web',
                'feature_id' => 4,
                'feature_name' => 'Gestion des rôles',
                'feature_description' => 'Gestion des rôles',
              ),
              20 =>
              array (
                'name' => 'modifier-role',
                'description' => 'Modifier un rôle',
                'guard_name' => 'web',
                'feature_id' => 4,
                'feature_name' => 'Gestion des rôles',
                'feature_description' => 'Gestion des rôles',
              ),
              21 =>
              array (
                'name' => 'supprimer-role',
                'description' => 'Supprimer un rôle',
                'guard_name' => 'web',
                'feature_id' => 4,
                'feature_name' => 'Gestion des rôles',
                'feature_description' => 'Gestion des rôles',
              ),
              22 =>
              array (
                'name' => 'configurer-role',
                'description' => 'Configurer un rôle',
                'guard_name' => 'web',
                'feature_id' => 4,
                'feature_name' => 'Gestion des rôles',
                'feature_description' => 'Gestion des rôles',
              ),
              23 =>
              array (
                'name' => 'voir-utilisateur',
                'description' => 'Voir les utilisateurs',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              24 =>
              array (
                'name' => 'ajouter-utilisateur',
                'description' => 'Ajouter un utilisateur',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              25 =>
              array (
                'name' => 'modifier-utilisateur',
                'description' => 'Modifier un utilisateur',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              26 =>
              array (
                'name' => 'supprimer-utilisateur',
                'description' => 'Supprimer un utilisateur',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              27 =>
              array (
                'name' => 'activer-utilisateur',
                'description' => 'Activer un utilisateur',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              28 =>
              array (
                'name' => 'rechercher-membre',
                'description' => 'rechercher-membre',
                'guard_name' => 'web',
                'feature_id' => 5,
                'feature_name' => 'Gestion des utilisateurs',
                'feature_description' => 'Gestion des utilisateurs',
              ),
              29 =>
              array (
                'name' => 'logsActivity',
                'description' => 'LogsActivity',
                'guard_name' => 'web',
                'feature_id' => 6,
                'feature_name' => 'Tools',
                'feature_description' => 'Tools',
              ),
              31 =>
              array (
                'name' => 'search',
                'description' => 'Rechercher',
                'guard_name' => 'web',
                'feature_id' => 6,
                'feature_name' => 'Tools',
                'feature_description' => 'Tools',
              ),
              32 =>
              array (
                'name' => 'stats-utilisateurs',
                'description' => 'Statistiques sur les utilisateurs',
                'guard_name' => 'web',
                'feature_id' => 8,
                'feature_name' => 'Statistiques',
                'feature_description' => 'Gestion des statistiques',
              ),
              33 =>
              array (
                'name' => 'voir-administrateur',
                'description' => 'Voir les administrateurs',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              34 =>
              array (
                'name' => 'modifier-administrateur',
                'description' => 'Modifier un administrateur',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              35 =>
              array (
                'name' => 'status-administrateur',
                'description' => 'Modifier le statut d\'un utilisateur',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              36 =>
              array (
                'name' => 'supprimer-administrateur',
                'description' => 'Supprimer un administrateur',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              37 =>
              array (
                'name' => 'activer-administrateur',
                'description' => 'Changer le statut d\'un administrateur',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              38 =>
              array (
                'name' => 'ajouter-administrateur',
                'description' => 'Ajouter un administrateur',
                'guard_name' => 'web',
                'feature_id' => 9,
                'feature_name' => 'Gestion des administrateurs',
                'feature_description' => 'Gestion des administrateurs',
              ),
              39 =>
              array (
                'name' => 'voir-application',
                'description' => 'Gestion de l\'application',
                'guard_name' => 'web',
                'feature_id' => 12,
                'feature_name' => 'Gestion de l\'application',
                'feature_description' => 'Gestion de l\'application',
              ),
              40 =>
              array (
                'name' => 'modifier-application',
                'description' => 'Modifier mon application',
                'guard_name' => 'web',
                'feature_id' => 12,
                'feature_name' => 'Gestion de l\'application',
                'feature_description' => 'Gestion de l\'application',
              ),
              42 =>
              array (
                'name' => 'voir-entreprise',
                'description' => 'Voir les entreprises',
                'guard_name' => 'web',
                'feature_id' => 13,
                'feature_name' => 'Gestion des entreprises',
                'feature_description' => 'Gestion des entreprises',
              ),
              44 =>
              array (
                'name' => 'modifier-entreprise',
                'description' => 'Modifier une entreprise',
                'guard_name' => 'web',
                'feature_id' => 13,
                'feature_name' => 'Gestion des entreprises',
                'feature_description' => 'Gestion des entreprises',
              ),
              46 =>
              array (
                'name' => 'status-entreprise',
                'description' => 'Changer le statut de l\'entreprise',
                'guard_name' => 'web',
                'feature_id' => 13,
                'feature_name' => 'Gestion des entreprises',
                'feature_description' => 'Gestion des entreprises',
              ),
          );

          foreach ($data as $value) {
              $p = DB::table('sid_permissions')->where('name', $value['name'])->first();
              if(empty($p)){
                  $feature = $this->feature($value);
                  DB::table('sid_permissions')->insertGetId([
                      'feature_id' => $feature,
                      'name' => $value['name'],
                      'description' => $value['description'],
                      'guard_name' => $value['guard_name'],
                      'created_at' => Carbon::now()->toDateTimeString(),
                      'updated_at' => Carbon::now()->toDateTimeString()
                  ]);
              }
          }

          // Roles
          DB::table('sid_roles')->insert([
              'name' => 'user',
              'description' => 'Default user',
              'guard_name' => 'web',
              'created_at' => Carbon::now()->toDateTimeString(),
              'updated_at' => Carbon::now()->toDateTimeString()
          ]);

          $role = Role::create(['guard_name' => 'web', 'name' => 'root', 'description' => 'Super administrateur']);
          $role->givePermissionTo(Permission::all());

          $admin = SidUser::first();
          $admin->assignRole($role);

      }

      public function feature($value){
          $item = DB::table('sid_features')->where('name', $value['feature_name'])->first();
          if(empty($item)){
              $item = DB::table('sid_features')->insertGetId([
                  'name' => $value['feature_name'],
                  'description' => $value['feature_description'],
                  'created_at' => Carbon::now()->toDateTimeString(),
                  'updated_at' => Carbon::now()->toDateTimeString()
              ]);
          } else {
              $item = $item->id;
          }
          return $item;

      }
}