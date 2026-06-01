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
    <title>Truper - Catálogo y Herramientas Profesionales </title>
    <style>
        :root {
            --truper-orange: #ff6600;
            --truper-orange-hover: #e65c00;
            --truper-dark: #1e293b;
            --light-gray: #f8fafc;
            --text-gray: #64748b;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-gray);
            color: var(--truper-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            background: var(--truper-dark);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .nav-brand {
            color: var(--truper-orange);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            text-decoration: none;
        }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            margin-left: 25px;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--truper-orange);
        }

        .btn-admin {
            background: var(--truper-orange);
            padding: 8px 16px;
            border-radius: 6px;
            color: white !important;
        }

        .btn-admin:hover {
            background: var(--truper-orange-hover);
            color: white !important;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(30, 41, 59, 0.8), rgba(30, 41, 59, 0.9)), url('https://images.unsplash.com/photo-1581147036324-c108428587d5?auto=format&fit=crop&q=80&w=1200') center/cover;
            color: var(--white);
            text-align: center;
            padding: 80px 20px;
        }
        
        .hero h1 {
            margin: 0;
            font-size: 3.5em;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 1.2em;
            max-width: 600px;
            margin: 0 auto;
            color: #cbd5e1;
            line-height: 1.6;
        }

        /* Secciones */
        section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            color: var(--truper-dark);
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .section-header p {
            color: var(--text-gray);
            font-size: 1.1em;
        }

        /* Grid de Productos */
        .grid-productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .producto-card {
            background: var(--white);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .producto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px -5px rgba(0,0,0,0.1);
            border-color: var(--truper-orange);
        }

        .producto-icono {
            font-size: 3.5em;
            margin-bottom: 15px;
            color: var(--truper-dark);
        }

        .producto-titulo {
            font-size: 1.2em;
            font-weight: 700;
            color: var(--truper-dark);
            margin: 10px 0;
            flex-grow: 1;
        }

        .producto-precio {
            font-size: 1.5em;
            font-weight: 800;
            color: var(--truper-orange);
            margin: 15px 0;
        }

        .producto-stock {
            font-size: 0.9em;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .stock-ok { background: #dcfce7; color: #166534; }
        .stock-low { background: #fef08a; color: #854d0e; }
        .stock-out { background: #fee2e2; color: #991b1b; }

        /* Footer */
        footer {
            background: var(--truper-dark);
            color: #94a3b8;
            text-align: center;
            padding: 30px 20px;
            margin-top: auto;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    
    <nav>
        <a href="#" class="nav-brand">TRUPER</a>
        <div class="nav-links">
            <a href="#productos">Catálogo</a>
            <a href="#mision">Nosotros</a>
            <a href="login.php" class="btn-admin">Panel Admin</a>
        </div>
    </nav>
    
    <header class="hero">
        <h1>Construye con los Mejores</h1>
        <p>Descubre nuestro catálogo de herramientas diseñadas para brindar la mayor resistencia, precisión y durabilidad en cada uno de tus proyectos.</p>
    </header>
    
    <section id="productos">
        <div class="section-header">
            <h2>Nuestros Productos</h2>
            <p>Catálogo actualizado de herramientas manuales y eléctricas en existencia</p>
        </div>
        
        <div class="grid-productos">
            <?php
            if (!$error_conexion) {
                $query = "SELECT id, nombre, precio, stock FROM herramientas ORDER BY id DESC";
                $resultado = $conexion->query($query);

                if ($resultado && $resultado->num_rows > 0) {
                    while($fila = $resultado->fetch_assoc()) {
                        
                        $stock_class = "stock-ok";
                        $stock_text = "En stock: " . $fila['stock'];
                        
                        if ($fila['stock'] == 0) {
                            $stock_class = "stock-out";
                            $stock_text = "Agotado";
                        } elseif ($fila['stock'] <= 5) {
                            $stock_class = "stock-low";
                            $stock_text = "Pocas piezas: " . $fila['stock'];
                        }

                        echo "<div class='producto-card'>";
                        echo "<div class='producto-icono'>🛠️</div>";
                        echo "<div class='producto-titulo'>" . htmlspecialchars($fila['nombre']) . "</div>";
                        
                        $precio = isset($fila['precio']) ? $fila['precio'] : 0;
                        echo "<div class='producto-precio'>$" . number_format($precio, 2) . "</div>";
                        
                        echo "<div class='producto-stock {$stock_class}'>{$stock_text}</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='grid-column: 1 / -1; text-align: center; font-size: 1.2em; color: var(--text-gray);'>No hay herramientas registradas en el inventario actualmente.</p>";
                }
                $conexion->close();
            } else {
                echo "<p style='color:red; grid-column: 1 / -1; text-align: center;'>Error de conexión: No se pudo conectar a la base de datos.</p>";
            }
            ?>
        </div>
    </section>
    
    <section id="mision" style="background: var(--white); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 60px;">
        <div class="section-header">
            <h2>Nuestra Filosofía</h2>
        </div>
        <div style="display: flex; gap: 40px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="color: var(--truper-orange);">Misión</h3>
                <p style="color: var(--text-gray); line-height: 1.6;">Proveer herramientas de la más alta calidad, con la mejor relación costo-beneficio, logrando la satisfacción total de nuestros clientes en cada uso.</p>
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h3 style="color: var(--truper-orange);">Visión</h3>
                <p style="color: var(--text-gray); line-height: 1.6;">Mantenernos como la marca líder en la fabricación y comercialización de herramientas en México y Latinoamérica, innovando constantemente.</p>
            </div>
        </div>
    </section>
    
    <footer>
        <p>&copy; 2026 Truper - Taller de Sistemas Operativos</p>
        <p></p>
    </footer>
</body>
</html>
