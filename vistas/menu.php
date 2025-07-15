<div class="col-sm-2 m-0 p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
    <nav class="nav-container m-0">
        <div class="row m-0 d-sm-none text-white">
            <center class="sidebar-profile"><img src="../public/img/user.png" alt="" class="mt-2 mx-center"></center>
            <?= $nombre; ?>
            <?= $apellido; ?>
        </div>
        <div class="publico m-0">
            <a href="./categoria.php" id="menu-categoria"><i class='bx bx-category'></i><span>Categoría</span></a>
           <a href="./producto.php" id="producto"><i class='bx bx-box'></i><span>Producto</span></a>
            <a href="./cliente.php" id="cliente"><i class='bx bx-user'></i><span>Cliente</span></a>
            <a href="./factura.php" id="factura"><i class='bx bx-receipt'></i><span>Factura</span></a> <!-- NUEVA SECCIÓN -->
        </div>
    </nav>
</div>
