<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <title>Warships - Naval Strategy Game</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        .landing-page {
            min-height: 100vh;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-section {
            text-align: center;
            margin-bottom: 4rem;
        }

        .hero-section h1 {
            font-size: 3rem;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-section p {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .auth-button {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .auth-button:hover {
            transform: translateY(-2px);
        }

        .sign-in {
            background: white;
            color: black;
        }

        .sign-up {
            background: var(--button-bg-secondary);
            color: white;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
        }

        .feature-card img {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="landing-page">
        <div class="hero-section">
            <h1>Welcome to Warships</h1>
            <p>Command your fleet, build your empire, and dominate the seas in this epic naval strategy game!</p>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="auth-button sign-in">Sign In</a>
                <a href="{{ route('register') }}" class="auth-button sign-up">Sign Up</a>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <img src="{{ asset('images/icons/directions.svg') }}" alt="Strategic Gameplay">
                <h3>Strategic Gameplay</h3>
                <p>Plan your moves, manage resources, and expand your naval empire through tactical decisions.</p>
            </div>
            <div class="feature-card">
                <img src="{{ asset('images/warships/1.svg') }}" alt="Fleet Management">
                <h3>Fleet Management</h3>
                <p>Build and customize your fleet with various warship types, each with unique capabilities.</p>
            </div>
            <div class="feature-card">
                <img src="{{ asset('images/islands/1.svg') }}" alt="Island Conquest">
                <h3>Island Conquest</h3>
                <p>Explore and conquer islands, establish trade routes, and defend your territory.</p>
            </div>
        </div>
    </div>
</body>
</html>
