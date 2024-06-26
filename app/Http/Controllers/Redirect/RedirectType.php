<?php

namespace App\Http\Controllers\Redirect;

abstract class RedirectType {

    public const DEFAULT_NOT_FOUND_MESSAGE = 'Ressource introuvable';
    public const DEFAULT_SUCCESS_MESSAGE = 'Opération effectuée avec succès';
    public const DEFAULT_FORBIDDEN_MESSAGE = 'Cette opération est impossible';
    public const DEFAULT_ERROR_MESSAGE = 'Une erreur est survenue durant cette opération';
    public const DEFAULT_WARNING_MESSAGE = 'Avertissement';
    public const DEFAULT_BACK_MESSAGE = 'Erreur';
    public const DEFAULT_VALIDATION_MESSAGE = 'Erreur: Verifiez que tous les champs sont corrects';
    public const DEFAULT_DELETED_MESSAGE = 'Suppression effectuée avec succès';
    public const DEFAULT_EDITED_MESSAGE = 'Modification effectuée avec succès';


    public const COLORS = [
        "danger"    => 'danger',
        "success"   => 'success',
        "warning"   => 'warning'
    ];

    static function REDIRECT_URI(string $uri, ?array $params = [], ?string $msg = null, ?string $color = null) {
        $msg = $msg ?? self::DEFAULT_NOT_FOUND_MESSAGE;
        $color = $color ?? self::COLORS['danger'];
        return redirect(route($uri, $params))->with('color', $color)->with('status', $msg);
    }

    static function REDIRECT_BACK(string $msg = null, string $color = null) {
        $msg = $msg ?? self::DEFAULT_BACK_MESSAGE;
        $color = $color ?? self::COLORS['danger'];
        return redirect()->back()->with('color', $color)
                                ->with('status', $msg);
    }

}