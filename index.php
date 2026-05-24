<?php
$host = "localhost";
$usuario = "dev_user";
$password = "ContrasenaDev123!";
$base_datos = "trupera";

$conexion = new mysqli($host, $usuario, $password, $base_datos);

$error_conexion = $conexion->connect_error ? true : false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truper - Catálogo y Herramientas Profesionales</title>
    <style>
        :root {
            --truper-orange: #ff6600;
            --truper-dark: #333333;
            --light-gray: #f4f4f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-gray);
            color: var(--truper-dark);
        }

        header {
            background: var(--truper-orange);
            color: var(--white);
            text-align: center;
            padding: 25px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        header h1 {
            margin: 0;
            font-size: 2.5em;
            letter-spacing: 1px;
        }

        nav {
            background: var(--truper-dark);
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav a {
            color: var(--white);
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--truper-orange);
        }

        section {
            padding: 50px 20px;
            max-width: 1200px;
            margin: 0 auto;
            background: var(--white);
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        h2 {
            color: var(--truper-orange);
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 10px;
            margin-top: 0;
        }

        .grid-productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .producto-card {
            background: var(--white);
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .producto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            border-color: var(--truper-orange);
        }

        .producto-icono {
            font-size: 3em;
            color: #777;
            margin-bottom: 15px;
        }

        .producto-titulo {
            font-size: 1.1em;
            font-weight: bold;
            color: var(--truper-dark);
            margin: 10px 0;
        }

        .producto-id {
            font-size: 0.9em;
            color: #888;
            background: #eee;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        footer {
            background: var(--truper-dark);
            color: var(--white);
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Truper</h1>
    </header>
    
    <nav>
        <a href="#inicio">Inicio</a> |
        <a href="#productos">Productos</a> |
        <a href="#mision">Misión y Visión</a> |
        <a href="login.php" style="color:var(--truper-orange);">Panel Admin</a>
    </nav>
    
    <section id="inicio">
        <h2>Bienvenido a Truper</h2>
        <p>Líder en herramientas en México. Explora nuestro catálogo diseñado para brindar la mayor resistencia y durabilidad en cada proyecto.</p>
    </section>
    
    <section id="productos">
        <h2>Nuestros Productos</h2>
        <p>Catálogo actualizado de herramientas manuales y eléctricas en existencia:</p>
        
        <div class="grid-productos">
            <?php
            if (!$error_conexion) {
                $query = "SELECT id, nombre FROM herramientas ORDER BY id ASC";
                $resultado = $conexion->query($query);

                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        echo "<div class='producto-card'>";
                        echo "<div class='producto-icono'>🔧</div>"; // Icono genérico
                        echo "<div class='producto-titulo'>" . htmlspecialchars($fila['nombre']) . "</div>";
                        echo "<div class='producto-id'>ID: " . htmlspecialchars($fila['id']) . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay herramientas registradas en el inventario actualmente.</p>";
                }
                $conexion->close();
            } else {
                echo "<p style='color:red;'>No se pudo conectar a la base de datos para cargar los productos.</p>";
            }
            ?>
        </div>
    </section>
    
    <section id="mision">
        <h2>Misión y Visión</h2>
        <p><strong>Misión:</strong> Proveer herramientas de la más alta calidad, con la mejor relación costo-beneficio, logrando la satisfacción total de nuestros clientes.</p>
        <p><strong>Visión:</strong> Mantenernos como la marca líder en la fabricación y comercialización de herramientas en México y Latinoamérica.</p>
    </section>
    
    <footer>
        <p>&copy; 2026 Truper - Taller de Sistemas Operativos | Desarrollado por el Equipo de Admin</p>
    </footer>
</body>
</html>
