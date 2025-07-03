<!DOCTYPE html>
<html>

<head>
    <title>PetStore App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container py-4">
    <h1 class="mb-4">
        <a href="{{ route('pets.index') }}" class="text-decoration-none text-dark">PetStore App</a>
    </h1>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    @yield('content')
</body>

</html>