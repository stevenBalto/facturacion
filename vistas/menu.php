<?php 
require __DIR__ . '/includes/auth_guard.php';
$usuario = $_SESSION['user']['username'] ?? 'Usuario';
?>
<div class="col-sm-2 m-0 p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
    <nav class="nav-container m-0">
        <div class="row m-0 d-sm-none text-white">
            <center class="sidebar-profile"><img src="../public/img/user.png" alt="" class="mt-2 mx-center"></center>
            <div class="text-center"><?= $usuario; ?></div>
        </div>
        <div class="publico m-0">
            <a href="./categoria.php" id="menu-categoria"><i class='bx bx-category'></i><span>Categoría</span></a>
           <a href="./producto.php" id="producto"><i class='bx bx-box'></i><span>Producto</span></a>
            <a href="./cliente.php" id="cliente"><i class='bx bx-user'></i><span>Cliente</span></a>
            <a href="./factura.php" id="factura"><i class='bx bx-receipt'></i><span>Factura</span></a>
            <hr class="text-white">
            <a href="#" id="btnLogout" class="text-danger"><i class='bx bx-log-out'></i><span>Cerrar Sesión</span></a>
        </div>
    </nav>
</div>

<script>
document.getElementById('btnLogout').addEventListener('click', async (e) => {
    e.preventDefault();
    if (confirm('¿Está seguro que desea cerrar sesión?')) {
        await fetch('../ajax/auth.php?accion=logout');
        location.href = '../login.php';
    }
});
</script>
