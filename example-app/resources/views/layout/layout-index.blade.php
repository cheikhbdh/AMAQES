<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>crud</title>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <link rel="stylesheet" href="{{ asset('assets/css/nav/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
</head>
<body>
<nav class="navbar">
    <div class="navbar-container container">
        <input type="checkbox" name="" id="">
        <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
        </div>
        <ul class="menu-items">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#testimonials" id="profile-link" class="nn">account</a></li>
        </ul>
        <div class="logo">
            <img src="{{ asset('assets/images/amaqes.png') }}" alt="Logo" height="30">
            <h3 style="display: inline-block; margin-left: 0px;">Autorité Mauritanienne d'Assurance <br>Qualité L'enseignemt Superieur</h3>
        </div>
        <div class="profile-dropdown">
            <a href="#" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
            <a href="#" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </div>
</nav>
@yield('content')
<footer class="footer">
    <div class="container">
        <p>Restraunt &copy; all rights reserved</p>
        <p>Developed by Dr/Abououbeidette</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var profileLink = document.getElementById('profile-link');
        var profileDropdown = document.querySelector('.profile-dropdown');

        profileLink.addEventListener('click', function(event) {
            event.preventDefault();
            profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Fermez le menu déroulant lorsque vous cliquez n'importe où en dehors de celui-ci
        window.addEventListener('click', function(event) {
            if (!event.target.matches('#profile-link')) {
                profileDropdown.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>

