<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Wallets\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicCommentController extends Controller
{
    public function show($walletId)
    {
        $wallet = Wallet::where('wallet_id', $walletId)
            ->where('is_under_distributer', true)
            ->with('sidUser')
            ->firstOrFail();

        return view('public.comment', compact('wallet'));
    }

    public function print($walletId)
    {
        $wallet = Wallet::where('wallet_id', $walletId)
            ->firstOrFail();

        abort_if(!$wallet->qr_comment, 404, 'QR Code non encore généré.');

        return view('public.comment-qr-print', compact('wallet'));
    }

    public function websitePrint()
    {
        $qrPath = 'qrcodes/website_robus.png';
        abort_if(!file_exists(public_path($qrPath)), 404, 'QR Code non encore généré.');

        return view('public.website-qr-print', compact('qrPath'));
    }

    public function store(Request $request, $walletId)
    {
        $wallet = Wallet::where('wallet_id', $walletId)
            // ->where('is_under_distributer', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'commenter_name' => 'required|string|max:100',
            'description'    => 'required|string|max:1000',
        ], [
            'commenter_name.required' => 'Votre nom est obligatoire.',
            'description.required'    => 'Votre commentaire est obligatoire.',
            'description.max'         => 'Le commentaire ne doit pas dépasser 1000 caractères.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Comment::create([
            'sid_user_id'         => null,
            'target_sid_user_id'  => $wallet->sid_user_id,
            'commenter_name'      => $request->commenter_name,
            'description'         => $request->description,
            'is_public'           => true,
        ]);

        return back()->with('success', 'Votre avis a été envoyé avec succès. Merci !');
    }
}
