<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "dev_user", "ContrasenaDev123!", "trupera");

if (isset($_POST['actualizar'])) {
    $id = (int)$_POST['id'];
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    
    $query = "UPDATE herramientas SET nombre='$nombre', precio='$precio', stock='$stock' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}

if (isset($_POST['insertar'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    
    $query = "INSERT INTO herramientas (nombre, precio, stock) VALUES ('$nombre', '$precio', '$stock')";
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    mysqli_query($conn, "DELETE FROM herramientas WHERE id=$id");
    header("Location: admin.php");
    exit();
}

$herramienta_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = (int)$_GET['editar'];
    $resultado_edit = mysqli_query($conn, "SELECT * FROM herramientas WHERE id=$id_editar");
    $herramienta_editar = mysqli_fetch_assoc($resultado_edit);
}

$resultado = mysqli_query($conn, "SELECT * FROM herramientas ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Truper</title>
    <style>
        :root {
            --primary-color: #ff6600;
            --primary-hover: #e65c00;
            --dark-bg: #1e293b;
            --light-bg: #f8fafc;
            --text-dark: #334155;
            --border-color: #cbd5e1;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--light-bg);
            margin: 0;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: var(--dark-bg);
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        header .brand {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 22px;
            letter-spacing: 0.5px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-links a:hover { opacity: 0.8; }
        
        .logout-btn {
            background: #ef4444;
            padding: 8px 18px;
            border-radius: 6px;
        }
        
        .logout-btn:hover { background: #dc2626; }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            display: flex;
            gap: 30px;
            padding: 0 20px;
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Estilos de Tarjetas */
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        }

        .form-section { flex: 1; height: fit-content; }
        .table-section { flex: 2; overflow-x: auto; }
        
        h3 {
            color: var(--dark-bg);
            margin-top: 0;
            font-size: 20px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        /* Formularios */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
            color: #475569;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: inherit;
        }

        input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
        }

        /* Botones */
        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s, transform 0.1s;
        }

        .btn-submit:hover { background: var(--primary-hover); }
        .btn-submit:active { transform: scale(0.98); }
        .btn-submit.btn-edit { background: #3b82f6; }
        .btn-submit.btn-edit:hover { background: #2563eb; }
        
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        tr:hover td { background-color: #f1f5f9; }
        
        /* Acciones de tabla */
        .action-link {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            margin-right: 5px;
            transition: opacity 0.3s;
        }
        
        .action-link:hover { opacity: 0.8; }
        .edit-link { background: #3b82f6; }
        .del-link { background: #ef4444; }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }
        
        .cancel-link:hover { text-decoration: underline; }

        /* Footer profesional */
        footer {
            background: var(--dark-bg);
            color: #94a3b8;
            text-align: center;
            padding: 15px 0;
            font-size: 13px;
            margin-top: auto;
        }

    </style>
</head>
<body>

    <header>
        <div class="brand">Truper Admin</div>
        <div class="nav-links">
            <a href="index.php">Ver Tienda</a>
            <a href="?logout=true" class="logout-btn">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container">
        <div class="card form-section">
            <?php if ($herramienta_editar): ?>
                <h3>Editar Herramienta</h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $herramienta_editar['id']; ?>">
                    
                    <div class="form-group">
                        <label>Nombre del producto:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($herramienta_editar['nombre']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Precio (MXN):</label>
                        <input type="number" name="precio" step="0.01" min="0" value="<?php echo $herramienta_editar['precio']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Stock disponible:</label>
                        <input type="number" name="stock" min="0" value="<?php echo $herramienta_editar['stock']; ?>" required>
                    </div>

                    <button type="submit" name="actualizar" class="btn-submit btn-edit">Guardar Cambios</button>
                    <a href="admin.php" class="cancel-link">Cancelar</a>
                </form>
            <?php else: ?>
                <h3>Agregar Herramienta</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Nombre del producto:</label>
                        <input type="text" name="nombre" placeholder="Ej. Taladro Percutor 1/2" required>
                    </div>

                    <div class="form-group">
                        <label>Precio (MXN):</label>
                        <input type="number" name="precio" step="0.01" min="0" placeholder="Ej. 1450.50" required>
                    </div>

                    <div class="form-group">
                        <label>Stock disponible:</label>
                        <input type="number" name="stock" min="0" placeholder="Ej. 25" required>
                    </div>

                    <button type="submit" name="insertar" class="btn-submit">Agregar Producto</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="card table-section">
            <h3>Inventario Actual</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Herramienta</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                    <td>
                        <?php
                        // Un pequeño toque visual para el stock
                        if($row['stock'] <= 5) {
                            echo "<span style='color:#ef4444; font-weight:bold;'>" . $row['stock'] . " (Bajo)</span>";
                        } else {
                            echo $row['stock'];
                        }
                        ?>
                    </td>
                    <td>
                        <a href="?editar=<?php echo $row['id']; ?>" class="action-link edit-link">Editar</a>
                        <a href="?eliminar=<?php echo $row['id']; ?>" class="action-link del-link" onclick="return confirm('¿Seguro que deseas eliminar esta herramienta?')">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <footer>
        Sistema administrado por Rafael Isay Martínez Zurita (No. Control: 24160711)
    </footer>

</body>
</html>
