<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tickerama Api Identifier</title>
</head>
<body>
    <p>Bienvenue Mr ou Mme {{ $full_name }} </p>
    <p>Voici vos identifiants de connexion à notre Api.</p>
    <p><strong>email : {{ $email }} </strong> </p>
    <p><strong>password : {{ $password }} </strong> </p>
    <p>
    Documentation de l'api : <a href="http://127.0.0.1.8000/api/documentation" target="_blank" rel="noopener noreferrer">
    http://127.0.0.1.8000/api/documentation
    </a>
    </p>
</body>
</html>