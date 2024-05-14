<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>crud</title>
   
  <link rel="stylesheet" href="{{ asset('assets/css/login/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
</head>
<body>
    <div class="login-form">
        <h1>Register</h1>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf <!-- Ajoutez ceci pour protéger votre formulaire contre les attaques CSRF -->
            <input type="text" name="name" placeholder="name" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="confirme Password" required>
            <button type="submit">register</button>
        </form>
       
        <a href="#">Forgot password?</a>
        <div class="signup-link">
            <p>Don't have an account? <a href="/">Sign in</a></p>
        </div>
    </div>
    
</body>
