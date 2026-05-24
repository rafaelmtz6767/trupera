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
    $id_actualizar = $_POST['id'];
    $nombre_nuevo = $_POST['nombre'];
    mysqli_query($conn, "UPDATE herramientas SET nombre='$nombre_nuevo' WHERE id=$id_actualizar");
    header("Location: admin.php");
    exit();
}

if (isset($_POST['insertar'])) {
    $nombre = $_POST['nombre'];
    mysqli_query($conn, "INSERT INTO herramientas (nombre) VALUES ('$nombre')");
}

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conn, "DELETE FROM herramientas WHERE id=$id");
    header("Location: admin.php");
    exit();
}

$herramienta_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
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
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
        header { background: #333; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header span { color: #ff6600; font-weight: bold; font-size: 20px; }
        .logout-btn { color: white; text-decoration: none; background: #e74c3c; padding: 8px 15px; border-radius: 4px; }
        .container { max-width: 1000px; margin: 30px auto; display: flex; gap: 30px; padding: 0 20px; }
        
        .form-section { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); flex: 1; height: fit-content; }
        .form-section h3 { color: #ff6600; margin-top: 0; }
        .form-section input[type="text"] { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
        .btn-submit { background: #ff6600; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        .btn-submit.btn-edit { background: #2980b9; }
        
        .table-section { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); flex: 2; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #333; color: white; }
        tr:hover { background-color: #f1f1f1; }
        
        .action-link { text-decoration: none; padding: 5px 10px; border-radius: 3px; color: white; font-size: 13px;}
        .edit-link { background: #2980b9; }
        .del-link { background: #e74c3c; }
    </style>
</head>
<body>

    <header>
        <span>Truper Admin</span>
        <div>
            <a href="index.php" style="color:white; margin-right: 15px; text-decoration: none;">Ver Tienda</a>
            <a href="?logout=true" class="logout-btn">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container">
        <!-- Columna Izquierda: Formularios -->
        <div class="form-section">
            <?php if ($herramienta_editar): ?>
                <h3>Editar Herramienta</h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $herramienta_editar['id']; ?>">
                    <label>Nombre del producto:</label>
                    <input type="text" name="nombre" value="<?php echo $herramienta_editar['nombre']; ?>" required>
                    <button type="submit" name="actualizar" class="btn-submit btn-edit">Guardar Cambios</button>
                    <a href="admin.php" style="display:block; text-align:center; margin-top:10px; color:#777; text-decoration:none;">Cancelar</a>
                </form>
            <?php else: ?>
                <h3>Agregar Herramienta</h3>
                <form method="POST">
                    <label>Nombre del producto:</label>
                    <input type="text" name="nombre" placeholder="Ej. Taladro Percutor 1/2" required>
                    <button type="submit" name="insertar" class="btn-submit">Agregar Producto</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Columna Derecha: Tabla -->
        <div class="table-section">
            <h3 style="color:#333; margin-top:0;">Inventario Actual</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Herramienta</th>
                    <th>Acciones</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td>
                        <a href="?editar=<?php echo $row['id']; ?>" class="action-link edit-link">Editar</a>
                        <a href="?eliminar=<?php echo $row['id']; ?>" class="action-link del-link" onclick="return confirm('¿Seguro que deseas eliminar esta herramienta?')">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

</body>
</html>
