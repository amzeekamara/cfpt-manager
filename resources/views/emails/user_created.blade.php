<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Création de votre compte</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f7f9fb;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #0f4c81;
            margin: 0;
        }

        .content {
            line-height: 1.6;
        }

        .credentials {
            background-color: #f2f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .credentials p {
            margin: 5px 0;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4B6A95;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #727780;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue sur CFPT Manager</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            <p>Votre compte a été créé avec succès sur la plateforme de gestion pédagogique <strong>CFPT Manager - CFPT
                    Sénégal-Japon</strong>.</p>

            <p>Voici vos informations de connexion :</p>
            <div class="credentials">
                <p><strong>URL de connexion :</strong> <a href="{{ route('login') }}">{{ route('login') }}</a></p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                @if ($password)
                    <p><strong>Mot de passe :</strong> {{ $password }}</p>
                @endif
            </div>

            @if ($password)
                <p><em>Note : Il vous sera demandé de modifier ce mot de passe lors de votre première connexion pour des
                        raisons de sécurité.</em></p>
            @endif

            <div class="button-container">
                <a href="{{ route('login') }}" class="button">Se connecter à mon compte</a>
            </div>
        </div>
        <div class="footer">
            <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} CFPT Manager - CFPT Sénégal-Japon. Tous droits réservés.</p>
        </div>
    </div>
</body>

</html>
