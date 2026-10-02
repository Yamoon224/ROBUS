# Annexe technique : contrôle automatique des boutons et des liens

Généré le 2 octobre 2026 par analyse statique du code (aucun clic réel dans l'application).
Chaque action de bouton Livewire (`wire:click`, `wire:submit`, `$wire.x`, composants take-action) est comparée aux méthodes du composant, et chaque `route()` aux routes déclarées.
Une ligne signalée peut être un faux positif (modale partagée entre plusieurs écrans, vue qui n'est plus utilisée) : à trier par l'équipe technique.

Déjà corrigé le 2 octobre : `pushs.invoice.print`, `djamos.invoice.print`, `tresortMoneys.invoice.print` (remplacés par les routes existantes) et `bankSends.invoice.print` (bouton masqué tant que la route n'existe pas).

```
Composants Livewire analysés : 236
Actions de boutons vérifiées : 658
Actions sans méthode correspondante : 31
  - App\Http\Livewire\Commissions\Onexbets :: updateTresort  [livewire/commissions/modals/transactions/transactionCosts/updateOnexbet.blade.php]
  - App\Http\Livewire\Commissions\Onexbets :: updateDjamo  [livewire/commissions/modals/transactions/transactionCosts/updateOnexbet.blade.php]
  - App\Http\Livewire\Commissions\Onexbets :: updatePush  [livewire/commissions/modals/transactions/transactionCosts/updateOnexbet.blade.php]
  - App\Http\Livewire\Commissions\VirementBanks :: updateTresort  [livewire/commissions/modals/transactions/transactionCosts/updateVersment.blade.php]
  - App\Http\Livewire\Commissions\VirementBanks :: updateDjamo  [livewire/commissions/modals/transactions/transactionCosts/updateVersment.blade.php]
  - App\Http\Livewire\Commissions\VirementBanks :: updatePush  [livewire/commissions/modals/transactions/transactionCosts/updateVersment.blade.php]
  - App\Http\Livewire\Companies\CompanieEmployers :: deconnectedOneCompanie  [livewire/modals/unsignUnderDistributors.blade.php]
  - App\Http\Livewire\Companies\UnderDistributors :: deconnectedOneCompanie  [livewire/modals/unsignUnderDistributors.blade.php]
  - App\Http\Livewire\ComZones\EnderDistributors :: resetField  [livewire/modals/disabledUsers.blade.php]
  - App\Http\Livewire\custumers :: disable  [livewire/modals/disabledUsers.blade.php, livewire/modals/deletedUsers.blade.php]
  - App\Http\Livewire\DrawalRemonte :: resetField  [livewire/modals/underDrawal.blade.php]
  - App\Http\Livewire\Historical\UnderHistorical\BankHisto :: updateVirementHisto  [livewire/historical/modals/updateVirementRefund.blade.php]
  - App\Http\Livewire\Historical\UnderHistorical\CarteHisto :: updateReloadCardHisto  [livewire/historical/modals/updateReloadCardDefund.blade.php]
  - App\Http\Livewire\Historical\UnderHistorical\PaymentHisto :: updatePaiementHisto  [livewire/historical/modals/updatePaiemntRefund.blade.php]
  - App\Http\Livewire\Historical\UnderHistorical\PhoneCreditHisto :: updatePhoneCreditHisto  [livewire/historical/modals/updatePhoneCreditDefund.blade.php]
  - App\Http\Livewire\Historical\UnderHistorical\TransferHisto :: updateTransactionHisto  [livewire/historical/modals/updateTransfersRefund.blade.php]
  - App\Http\Livewire\Masters\Dumps :: cancelReloadCardField  [livewire/commissions/modals/transactions/masters/adddumps.blade.php]
  - App\Http\Livewire\Masters\Reloads :: cancelReloadCardField  [livewire/commissions/modals/transactions/masters/addReload.blade.php]
  - App\Http\Livewire\Rankings\WalletIntersRankings :: resetFilters  [components/searchInterRankings.blade.php]
  - App\Http\Livewire\Reloaduv :: updateReload  [livewire/commissions/modals/transactions/updateReload.blade.php]
  - App\Http\Livewire\Searchcountrie :: showOrders  [livewire/searchcountrie.blade.php]
  - App\Http\Livewire\Service :: add  [livewire/commissions/modals/providers/addProvider.blade.php]
  - App\Http\Livewire\ServiceVisitors :: resetField  [livewire/commissions/modals/providers/deleteProvider.blade.php]
  - App\Http\Livewire\ServiceVisitors :: delete  [livewire/commissions/modals/providers/deleteProvider.blade.php]
  - App\Http\Livewire\TransactionGroupe\TransactionNationCentral :: cancelInterOutCivField  [livewire/transaction-groupe/transaction-nation-central.blade.php]
  - App\Http\Livewire\TransactionGroupe\TransactionNationWest :: cancelInterOutCivField  [livewire/transaction-groupe/transaction-nation-west.blade.php]
  - App\Http\Livewire\TransactionInters\TransactionCentrale :: updateProviderTransaction  [livewire/transaction-inters/transaction-centrale.blade.php]
  - App\Http\Livewire\TransactionInters\TransactionCentrale :: addInterOut  [livewire/transaction-inters/transaction-centrale.blade.php]
  - App\Http\Livewire\TransactionInters\TransactionCentrale :: validatedTransaction  [livewire/transaction-inters/transaction-centrale.blade.php]
  - App\Http\Livewire\UserAdmin :: deletedUser  [livewire/modals/deletedUsers.blade.php]
  - App\Http\Livewire\UsersVisites :: setSortField  [livewire/users-visites.blade.php]
Classes non chargées : 1
  - App\Http\Livewire\HomeRobusAncien

Noms de routes utilisés : 474
Routes utilisées mais non définies : 44
  - bankSends.invoice.print  [resources/views/components/bank_send_compo.blade.php]
  - {$routePrefix}.activate  [resources/views/components/details_liste_custumers.blade.php]
  - {$routePrefix}.show  [resources/views/components/details_liste_custumers.blade.php]
  - home  [resources/views/errors/illustrated-layout.blade.php]
  - compliance.update  [resources/views/pages/account/compliance/index.blade.php]
  - teams.delete  [resources/views/pages/account/teams/index.blade.php]
  - teams.add  [resources/views/pages/account/teams/index.blade.php]
  - banners.store  [resources/views/pages/admin/bannieres/add.blade.php]
  - banners.show  [resources/views/pages/admin/bannieres/browse.blade.php]
  - banners.update  [resources/views/pages/admin/bannieres/edit.blade.php]
  - pages.store  [resources/views/pages/admin/pages/add.blade.php]
  - pages.show  [resources/views/pages/admin/pages/browse.blade.php]
  - pages.update  [resources/views/pages/admin/pages/showUpdate.blade.php]
  - about.upload  [resources/views/pages/admin/pages/showUpdate.blade.php, resources/views/pages/notifications/single.blade.php]
  - managers.browse  [resources/views/pages/admin/showProfileMan.blade.php]
  - movements.browse  [resources/views/pages/admin/showProfileMan.blade.php]
  - zones.store  [resources/views/pages/admin/zones/add.blade.php]
  - zones.add  [resources/views/pages/admin/zones/browse.blade.php]
  - zones.activate  [resources/views/pages/admin/zones/browse.blade.php]
  - zones.show_detail  [resources/views/pages/admin/zones/browse.blade.php]
  - zones.show  [resources/views/pages/admin/zones/browse.blade.php]
  - zones.delete  [resources/views/pages/admin/zones/browse.blade.php]
  - zones.update  [resources/views/pages/admin/zones/edit.blade.php]
  - brands.store  [resources/views/pages/custumers/add.blade.php]
  - brands.update  [resources/views/pages/custumers/edit.blade.php]
  - orders.browse  [resources/views/pages/notifications/browse.blade.php]
  - orders.progressOrder  [resources/views/pages/notifications/browse.blade.php]
  - orders.show  [resources/views/pages/notifications/browse.blade.php]
  - orders.invoice.print  [resources/views/pages/notifications/browse.blade.php]
  - orders.destroy  [resources/views/pages/notifications/browse.blade.php]
  - notifications.multiple_notifis  [resources/views/pages/notifications/multiple.blade.php]
  - notifications.single_notif  [resources/views/pages/notifications/single.blade.php]
  - category.store  [resources/views/pages/settings/add.blade.php]
  - category.add  [resources/views/pages/settings/browse.blade.php]
  - category.activate  [resources/views/pages/settings/browse.blade.php]
  - category.show  [resources/views/pages/settings/browse.blade.php]
  - category.delete  [resources/views/pages/settings/browse.blade.php]
  - category.update  [resources/views/pages/settings/edit.blade.php]
  - l5-swagger.  [resources/views/vendor/l5-swagger/index.blade.php]
  - admins.browse  [app/Http/Controllers/Admin/AdminController.php]
  - pages.browse  [app/Http/Controllers/Admin/PageController.php]
  - compliance  [app/Http/Controllers/ComplianceController.php]
  - users.browse  [app/Http/Controllers/FirmController.php, app/Http/Controllers/UserController.php]
  - payments.show  [app/Http/Livewire/Searchcountrie.php]
```
