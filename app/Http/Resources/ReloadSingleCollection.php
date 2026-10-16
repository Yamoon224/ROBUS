<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;

class ReloadSingleCollection extends JsonResource
{
    use \App\Http\Resources\Concerns\BatchesLazyLoads;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Les dépôts Pixel Innov enregistrent le mobile crédité dans metadata.
        // Ne jamais exposer le téléphone du titulaire du wallet comme bénéficiaire.
        $destination = is_array($this->metadata)
            ? ($this->metadata['destination'] ?? null)
            : null;
        // Source de paiement d'un dépôt/retrait Pixel Innov :
        //  - 'robus'        : le solde Robus a été débité (Cash In) ;
        //  - 'mobile_money' : l'opération passe par le mobile money (Orange, Moov, MTN…),
        //                     le solde Robus n'a pas à être affiché.
        $isPixelOperation = in_array($this->type_transaction, ['Cash In', 'Cash Out'], true);
        $paymentSource = null;
        if ($isPixelOperation) {
            $paymentSource = ($this->type_transaction === 'Cash In' || $this->payment_method === 'robus_wallet')
                ? 'robus'
                : 'mobile_money';
        }

        $robusBalanceBefore = null;
        $robusBalanceAfter = null;
        if ($paymentSource === 'robus') {
            $balanceCode = optional(optional($this->wallet)->currencie)->code ?? 'CFA';
            $before = (float) $this->amount_solde; // solde Robus relevé avant le débit
            $after = in_array($this->status, ['ERROR', 'FAILED'], true)
                ? $before // débit remboursé
                : $before - ((float) $this->amount + (float) $this->fees);
            $robusBalanceBefore = number_format($before, 2, '.', ' ') . ' ' . $balanceCode;
            $robusBalanceAfter = number_format($after, 2, '.', ' ') . ' ' . $balanceCode;
        }

        $wallet_send = Wallet::where('wallet_id',  $this->account_wallet)->first();

        if ($wallet_send != null) {
            $wallet_send = Wallet::where('wallet_id',  $this->account_wallet)->first();
        } else {

            $wallet_send = null;
        }

        return [
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  number_format((float)$this->amount, 2, '.', ' '),
            // 'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'name_user_desti' =>  null,
            // 'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname). ' ' . (optional($wallet_send)->sidUser->lastname)  : null,
            'phone_user_desti' => $destination,
            'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname) : null,
            // 'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname)  : null,
            'phone_user_send' => optional($this->wallet)->sidUser->phone ?? null,
            'date_created' =>  $this->created_at,
            'type'  =>  $this->type_transaction ?? $this->payment_method,
            'type_received'  => $this->type_received,
            'phone'  => null,
            'date_treatment' =>  optional($this->paymentReload)->created_at,
            'Reference'  => optional($this->paymentReload)->transaction_id ?? $this->reference,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' => number_format((float)$this->commissions, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA")  ?? null,
            'number_decoder' => null,
            'num_wallet' =>  $this->account_wallet,
            'id_invoice' =>  null,
            'offer' => null,
            'number_contract' =>  null,
            'amount_received' =>  number_format((float) $this->amount_received, 2, '.', ' ') ,
            'number_phone' => null,
            'paiementChoix_phone' =>  null,
            'factureChoix_type' =>  null,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') ?? null,
            'devise' => $this->wallet && $this->wallet->currencie
                ? $this->wallet->currencie->code
                : "FCFA",
            // optional($this->currencie)->code
            'status'  =>  $this->status,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account' => ($this->amount_solde ?? '0') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'amount_solde_drawal' => ($this->amount_solde_drawal ?? '0') . ' ' .(optional(optional($wallet_send)->currencie)->code ?? "CFA"),
            'amount_solde_before_drawal' => ($this->amount_solde_before_drawal ?? '0') . ' ' . (optional(optional($wallet_send)->currencie)->code ?? "CFA"),
            'cost_conversion' => ($this->cost_conversion ?? '0') . ' ' . (optional(optional($wallet_send)->currencie)->code ?? "CFA"),
            'amount_conversion' => ($this->amount_conversion ?? '0') . ' ' . (optional(optional($wallet_send)->currencie)->code ?? "CFA"),
            'payment_source' => $paymentSource,
            'robus_balance_before' => $robusBalanceBefore,
            'robus_balance_after' => $robusBalanceAfter,
        ];
    }
}
