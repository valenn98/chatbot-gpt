<!DOCTYPE html>
<html>
<head>
    <title>Contexto Empresa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }
        .container {
            margin-top: 50px;
            max-width: 1000;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
            color: #555;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            height: 150px; /* Ajusta esta altura según tus necesidades */
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .alert {
            margin-top: 20px;
        }
        .text-center {
            margin-bottom: 30px;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 300;
        }
        .stats {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .stats p {
            margin: 0;
        }
        textarea.form-control {
            height: 100vh;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Vitta Derm</h1>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('update.context') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="context">Nuevo Contexto:</label>
            <textarea id="context" name="context" class="form-control" rows="8" placeholder="Ingrese el nuevo contexto aquí">{{ $context }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Actualizar</button>
    </form>
    
</div>
</body>
</html>
