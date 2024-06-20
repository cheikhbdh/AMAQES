<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lang.css') }}">
    <title>{{ __('messages.Home') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center {{ app()->getLocale() == 'ar' ? 'arabic-layout' : '' }}">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/amaqes2.png') }}" alt="Logo" width="20%">
        <span class="d-none d-lg-block" style="font-size: 12px;">{{ __('messages.logo1') }}<br>{{ __('messages.logo2') }}</span>
      </a>
      <i id="toggleSidebar" class="bi bi-list toggle-sidebar-btn sidebar-icon"></i>
    </div><!-- End Logo -->

    

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        
        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="langue-dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-translate"></i><span>{{ __('messages.Langue') }}</span>
  </a>
  <ul class="dropdown-menu" aria-labelledby="langue-dropdown">
    <li>
      <a class="dropdown-item" href="{{ route('setlocale', ['locale' => 'fr']) }}">
        <i></i><span>Français</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('setlocale', ['locale' => 'ar']) }}">
        <i></i><span>العربية</span>
      </a>
    </li>
  </ul>
</li>

      
     

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @if(session('user_email')=='Mhaless@hotmail.com')
              <img src="assets/img/amaqes.jpg" alt="Profile" class="rounded-circle">
@else
<img src="assets/img/amaqes2.png" alt="Profile" class="rounded-circle">
@endif
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ session('user_name') }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ session('user_name') }}</h6>
              <span>Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                <i class="bi bi-person"></i>
                <span>Mon Profil</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <form action="{{ route('logout') }}" method="get">
                    @csrf
                    <a class="dropdown-item d-flex align-items-center">
                      <i class="bi bi-box-arrow-right"></i>
                    <span><button type="submit" style="border: none; background: none; padding: 0; font: inherit; cursor: pointer;">Déconnexion</button></span>
                   
                  </a>
                </form>  
        </li>

      </ul>
    </nav>

  </header>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar {{ app()->getLocale() == 'ar' ? 'sidebar-right' : '' }}">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{ route('dashadmin') }}">
          <i class="bi bi-grid"></i>
          <span>{{ __('messages.Dashboard') }}</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>{{ __('messages.Components') }}</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('show.referent') }}">
              <i class="bi bi-circle"></i><span>{{ __('messages.référentiels') }}</span>
            </a>
          </li>
          <li>
            <a href="{{route('invitations.index')}}">
              <i class="bi bi-circle"></i><span>{{ __('messages.campagnes') }}</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>{{ __('messages.Gestions EES') }}</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('institutions.index')}}">
              <i class="bi bi-circle"></i><span>{{ __('messages.Les institutions') }}</span>
            </a>
          </li>
          <li>
            <a href="{{route('etablissement.index')}}">
              <i class="bi bi-circle"></i><span>{{ __('messages.Les Établissements') }}</span>
            </a>
          </li>
          <li>
            <a href="{{route('departement.index')}}">
              <i class="bi bi-circle"></i><span> Départements</span>
            </a>
          </li>
            <li class="nav-item1">
              <a href="#" data-bs-target="#filiere-nav" data-bs-toggle="collapse">
                <i class="bi bi-circle"></i><span>Filières</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="filiere-nav" class="nav-content collapse" data-bs-parent="#components-nav"style="padding-left: 15px;">
                <li>
                  <a href="{{route('filiere.index')}}">
                    <i class="bi bi-circle"></i><span>Licence</span>
                  </a>
                </li>
                <li>
                  <a href="{{route('filiere.indexM')}}">
                    <i class="bi bi-circle"></i><span>Master</span>
                  </a>
                </li>
                <li>
                  <a href="{{route('filiere.indexD')}}">
                    <i class="bi bi-circle"></i><span>Doctorat</span>
                  </a>
                </li>
              </ul>
            </li>
        </ul>
      </li><!-- End Forms Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>{{ __('messages.Gestions') }}</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.utilisateurs') }}">
              <i class="bi bi-circle"></i><span>{{ __('messages.Les admins') }}</span>
            </a>
          </li>
          <li>
            <a href="{{ route('evaluateur_in.utilisateurs') }}">
              <i class="bi bi-circle"></i><span>{{ __('messages.Les évaluateur_interne') }}</span>
            </a>
          </li>
        </li>
        <li>
          <a href="{{ route('evaluateur_ex.utilisateurs') }}">
            <i class="bi bi-circle"></i><span>{{ __('messages.Les évaluateur_externe') }}</span>
          </a>
        </li>

          
        </ul>
      </li><!-- End Tables Nav -->
      
      <li class="nav-heading">{{ __('messages.Pages') }}</li>
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('profile') }}">
          <i class="bi bi-person"></i>
          <span>{{ __('messages.Profile') }}</span>
        </a>
      </li><!-- End Profile Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  

    @yield('content')

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      Tous Droit Réservés &copy; 2024 <a href="https://amaqes.mr//"><strong><span>AMAQES</span></strong></a>. 
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Développé par <a href="http://supnum.mr/"><strong><span>SupNum</span></strong></a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <style>
    /* Styles pour la mise en page arabe */
    .arabic-layout {
      direction: rtl; /* Inverser la direction du texte */
    }
  
    /* Ajoutez d'autres styles CSS pour ajuster la mise en page en arabe si nécessaire */
  </style>
  <script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        
        sidebar.classList.toggle('hidden');
        main.classList.toggle('expanded');
    });
    </script>
      

</body>

</html>