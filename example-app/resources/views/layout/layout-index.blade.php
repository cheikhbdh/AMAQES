<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>evaluateur_interne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/nav/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container container">
            <input type="checkbox" id="menu-toggle">
            <div class="hamburger-lines">
                <label for="menu-toggle">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </label>
            </div>
            <ul class="menu-items">
                <!-- Menu de langue -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('messages.Langue') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('setlocale', ['locale' => 'fr']) }}">Français</a></li>
                        <li><a class="dropdown-item" href="{{ route('setlocale', ['locale' => 'ar']) }}">العربية</a></li>
                    </ul>                    
                </li>                
                <li><a href="#home">{{ __('messages.Accueil') }}</a></li>
                <li><a href="#about">{{ __('messages.À_propos') }}</a></li>
                <li><a href="#testimonials" id="profile-link">{{ __('messages.Compte') }}</a></li>
            </ul>
            <div class="logo">
                <img src="{{ asset('assets/img/amaqes2.png') }}" alt="Logo" height="30">
                <h3 style="display: inline-block; margin-left: 0px;">{{ __('messages.logo1') }}<br>{{ __('messages.logo2') }}</h3>
            </div>
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="#" class="dropdown-item"><i class="fas fa-user"></i> {{ __('messages.Profile') }}</a>          
                <a href="{{ route('logout') }}" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> {{ __('messages.Déconnexion') }}</a>
            </div>
        </div>
    </nav>
    @yield('content')
    <footer id="footer" class="footer">
        <div class="copyright">
            Tous Droit Réservés &copy; 2024 <a href="https://amaqes.mr/"><strong><span>AMAQES</span></strong></a>. 
        </div>
        <div class="credits">
            Développé par <a href="http://supnum.mr/"><strong><span>SupNum</span></strong></a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var profileLink = document.getElementById('profile-link');
            var profileDropdown = document.getElementById('profile-dropdown');
    
            profileLink.addEventListener('click', function(event) {
                event.preventDefault();
                profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
            });
    
            window.addEventListener('click', function(event) {
                if (!profileLink.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.style.display = 'none';
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
