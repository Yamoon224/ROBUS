<?php

namespace App\Constants;

class NotificationMessages
{
    /* ============================
     * ADMIN – DEMANDES
     * ============================ */

    public const ADMIN_BANK_TRANSFER_REQUEST =
        'Nouvelle demande de versement bancaire';

    public const ADMIN_PAYMENT_REQUEST =
        'Nouvelle demande de paiement de facture';

    /* ============================
     * UTILISATEUR
     * ============================ */

    public const USER_PAYMENT_PENDING =
        'Votre demande est en cours de validation';

    public const WALLET_RELOAD_SUCCESS =
        'Votre portefeuille a été rechargé avec succès';

    /* ============================
     * RECHARGEMENTS & TRANSACTIONS
     * ============================ */

    public const WALLET_RELOAD_CARD =
        'Nouvelle demande de rechargement par carte';

    public const ORDER_RELOAD_CARD =
        'Nouvelle commande de carte';

    public const PHONE_CREDIT_RELOAD_CARD =
        'Nouvelle demande de rechargement de crédit téléphonique';

    public const WALLET_RELOAD_PUSH =
        'Nouvelle demande de transaction Push';

    public const WALLET_RELOAD_TRESORT =
        'Nouvelle demande de transaction Tresor Money';

    public const WALLET_RELOAD_DJAMO =
        'Nouvelle demande de transaction Djamo';

    public const WALLET_RELOAD_VERSEMENT =
        'Nouvelle demande de versement bancaire';

    public const WALLET_RELOAD_VIREMENT =
        'Nouvelle demande de virement bancaire';

    public const WALLET_RELOAD_INTER =
        'Nouvelle demande de transaction internationale';

    public const WALLET_RELOAD_XBET =
        'Nouvelle demande de transaction XBet';

    public const WALLET_RELOAD_WITH =
        'Nouvelle demande de rechargement';

    public const WALLET_RELOAD_BANK =
        'Nouvelle demande de rechargement Bank Send';

    public const WALLET_RELOAD_WAVE =
        'Nouvelle demande de transaction Wave';

    public const WALLET_RELOAD_ROBUS =
        'Nouvelle demande de transaction Robus';
    
     public const NEW_INTER_TRANSACTION =
        'Nouvelle demande de transaction internationale';
}
