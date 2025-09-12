<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Email | AutoDistribution</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo-text">AutoDistribution </div>
        </div>

        <h2>Vérifiez votre email</h2>

        <div class="message" style="color: var(--text-light); margin-bottom: 2rem; text-align: center;">
            Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="success">
                Un nouveau lien de vérification a été envoyé à votre adresse email.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">
                Renvoyer l'email de vérification
            </button>
        </form>

        <div class="links">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background: none; border: none; color: var(--text-light); cursor: pointer; opacity: 0.8;">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</body>
</html> 