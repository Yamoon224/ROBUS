<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un avis — ROBUS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            max-width: 480px;
            width: 100%;
            padding: 2rem 1.5rem;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .logo img { height: 40px; }
        .agent-info {
            text-align: center;
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .agent-avatar {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #3b5bdb;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }
        .agent-name { font-size: 1.15rem; font-weight: 700; color: #1e293b; }
        .agent-id { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
        h2 { font-size: 1rem; font-weight: 600; color: #374151; margin-bottom: 1.25rem; text-align: center; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.4rem; }
        input[type="text"], textarea {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-family: inherit;
            color: #1e293b;
            transition: border-color 0.2s;
            outline: none;
        }
        input[type="text"]:focus, textarea:focus { border-color: #3b5bdb; }
        textarea { resize: vertical; min-height: 120px; }
        .btn-submit {
            width: 100%;
            background: #3b5bdb;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.8rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-submit:hover { background: #2f4ac5; }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
            text-align: center;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }
        .char-count { font-size: 0.75rem; color: #94a3b8; text-align: right; margin-top: 0.25rem; }
        footer { text-align: center; margin-top: 1.5rem; font-size: 0.75rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('dist/images/logo_orange.png') }}" alt="ROBUS">
        </div>

        <div class="agent-info">
            <div class="agent-avatar">
                {{ strtoupper(substr($wallet->sidUser->firstname ?? 'R', 0, 1)) }}
            </div>
            <div class="agent-name">
                {{ $wallet->sidUser->firstname ?? '' }} {{ $wallet->sidUser->lastname ?? '' }}
            </div>
            <div class="agent-id">Agent ROBUS · {{ $wallet->wallet_id }}</div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <h2>Donnez votre avis sur cet agent</h2>

        <form method="POST" action="{{ route('public.comment.store', $wallet->wallet_id) }}">
            @csrf
            <div class="form-group">
                <label for="commenter_name">Votre nom</label>
                <input type="text" id="commenter_name" name="commenter_name"
                    value="{{ old('commenter_name') }}"
                    placeholder="Ex: Jean Dupont" maxlength="100" required>
            </div>
            <div class="form-group">
                <label for="description">Votre commentaire</label>
                <textarea id="description" name="description"
                    placeholder="Partagez votre expérience avec cet agent..."
                    maxlength="1000" required
                    oninput="document.getElementById('char-count').textContent = this.value.length + '/1000'">{{ old('description') }}</textarea>
                <div class="char-count" id="char-count">0/1000</div>
            </div>
            <button type="submit" class="btn-submit">Envoyer mon avis</button>
        </form>

        <footer>ROBUS — Plateforme de transfert d'argent</footer>
    </div>
</body>
</html>
