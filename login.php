<?php
session_start();
$mensaje_error = "";

if ($_POST) {
    $usuarios_validos = [
        "24160711@itoaxaca.edu.mx" => "24160711",
        "24160807@itoaxaca.edu.mx" => "24160807"
    ];

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (array_key_exists($email, $usuarios_validos) && $usuarios_validos[$email] === $password) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $mensaje_error = "Credenciales incorrectas. Acceso denegado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo - Truper</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }
        .login-container h2 {
            color: #ff6600;
            margin-top: 0;
            margin-bottom: 25px;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff6600;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 15px;
        }
        .login-container button:hover {
            background-color: #e65c00;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Panel Admin Isay</h2>
        <?php if($mensaje_error != "") echo "<div class='error'>$mensaje_error</div>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Correo institucional" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
