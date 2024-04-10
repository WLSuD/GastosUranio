<section class="full-box nav-lateral">
    <div class="full-box nav-lateral-bg show-nav-lateral"></div>
    <div class="full-box nav-lateral-content scroll">
        <figure class="full-box nav-lateral-avatar">
            <i class="far fa-times-circle show-nav-lateral"></i>
            <img src="<?php echo SERVERURL; ?>vistas/assets/avatar/<?php echo $_SESSION['foto_svi']; ?>" class="img-fluid" alt="Avatar">
            <figcaption class=" text-center">
            <?php echo $_SESSION['nombre_svi']; ?><br><small class=""><?php echo $_SESSION['cargo_svi']; ?></small>
            </figcaption>
        </figure>
        <div class="full-box nav-lateral-bar"></div>
        <nav class="full-box nav-lateral-menu">
            <ul>
                <li>
                    <a href="<?php echo SERVERURL; ?>dashboard/">
                        <i class="fab fa-dashcube fa-fw"></i> &nbsp; Dashboard
                    </a>
                </li>

                <!--- INICIO VISTA MENU LATERAL ADMINISTRADOR --->
                <?php if($_SESSION['cargo_svi']=="Administrador"){ ?>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-briefcase fa-fw"></i> &nbsp; Administración <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <!--<li> 
                            <a href="<?php echo SERVERURL; ?>cashier-new/">
                                <i class="fas fa-cash-register fa-fw"></i> &nbsp; Nueva caja
                            </a>
                        </li>
                        -->
                        <li>
                            <a href="<?php echo SERVERURL; ?>asignacion-new/">
                                <i class="fas fa-rectangle-list fa-fw"></i> &nbsp; Nueva Asignacion
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>category-new/">
                                <i class="fas fa-layer-group fa-fw"></i> &nbsp; Nueva Categoría
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>documento-new/">
                                <i class="fas fa-file-lines fa-fw"></i> &nbsp; Nuevo Documento
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>subcategoria-new/">
                                <i class="fas fa-list fa-fw"></i> &nbsp; Nueva SubCategoria
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>tComprobante-new/">
                                <i class="fas fa-file-lines fa-fw"></i> &nbsp; Nuevo Comprobante
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>tComprobante-new/">
                                <i class="fas fa-money-check-dollar fa-fw"></i> &nbsp; Nuevo tipo de gasto
                            </a>
                        </li>
                        <!--
                        <li>
                            <a href="<?php echo SERVERURL; ?>provider-new/">
                                <i class="fas fa-shipping-fast fa-fw"></i> &nbsp; Nuevo proveedor
                            </a>
                        </li>-->
                        <li>
                            <a href="<?php echo SERVERURL; ?>user-new/">
                                <i class="fas fa-user-tie fa-fw"></i> &nbsp; Nuevo Usuario
                            </a>
                        </li>                       
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-city fa-fw"></i> &nbsp; Empresa <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>empresa-new/">
                                <i class="fas fa-building fa-fw"></i> &nbsp; Nueva empresa
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-people-carry-box fa-fw"></i> &nbsp; Proveedor <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>proveedor-new/">
                                <i class="fas fa-dolly fa-fw"></i> &nbsp; Nuevo proveedor
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-trowel-bricks fa-fw"></i> &nbsp; Obra <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>obra-new/">
                                <i class="fas fa-trowel fa-fw"></i> &nbsp; Nueva Obra
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-landmark-flag fa-fw"></i> &nbsp; Entidades <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>entidad-new/">
                                <i class="fas fa-landmark-dome fa-fw"></i> &nbsp; Nueva Entidad
                            </a>
                        </li>
                    </ul>
                </li>     
                <!--<li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Ingreso <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>ingreso-new/">
                                <i class="fas fa-hand-holding-dollar fa-fw"></i> &nbsp; Nuevo Ingreso
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>ingreso-lista/">
                                <i class="fas fa-sack-dollar fa-fw"></i> &nbsp; Lista de Ingresos
                            </a>
                        </li>
                    </ul>
                </li>   
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-sack-xmark fa-fw"></i> &nbsp; Gasto <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>gasto-new/">
                                <i class="fas fa-money-bill-transfer fa-fw"></i> &nbsp; Nuevo Gasto
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>gasto-lista/">
                                <i class="fas fa-sack-xmark fa-fw"></i> &nbsp; Lista de Gastos
                            </a>
                        </li>
                    </ul>
                </li>   
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-calendar-days fa-fw"></i> &nbsp; Programa <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>programa-new/">
                                <i class="fas fa-calendar-plus fa-fw"></i> &nbsp; Nuevo Programa
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>programa-lista/">
                                <i class="fas fa-calendar-minus fa-fw"></i> &nbsp; Lista de Programas
                            </a>
                        </li>
                    </ul>
                </li>     
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-shopping-basket fa-fw"></i> &nbsp; Compras <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>shop-new/">
                                <i class="fas fa-shopping-bag fa-fw"></i> &nbsp; Nueva compra
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>shop-list/">
                                <i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp; Compras realizadas
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>shop-search/">
                                <i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar compra
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-truck-loading fa-fw"></i> &nbsp; Devoluciones <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>return-list/">
                                <i class="fas fa-people-carry fa-fw"></i> &nbsp; Devoluciones realizadas
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>return-search/">
                                <i class="fas fa-dolly-flatbed fa-fw"></i> &nbsp; Buscar devoluciones
                            </a>
                        </li>
                    </ul>
                </li>                
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-warehouse fa-fw"></i> &nbsp; Kardex <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>kardex/">
                                <i class="fas fa-pallet fa-fw"></i> &nbsp; Kardex general
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>kardex-search/">
                                <i class="fas fa-search fa-fw"></i> &nbsp; Buscar kardex
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>kardex-product/">
                                <i class="fas fa-luggage-cart fa-fw"></i> &nbsp; Kardex por producto
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="far fa-file-pdf fa-fw"></i> &nbsp; Reportes <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>report-sales/">
                                <i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Reportes de ventas
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>report-inventory/">
                                <i class="fas fa-box-open fa-fw"></i> &nbsp; Reportes de inventario
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-cogs fa-fw"></i> &nbsp; Configuraciones <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>company/">
                                <i class="fas fa-store-alt fa-fw"></i> &nbsp; Datos de la empresa
                            </a>
                        </li>
                    </ul>
                </li>-->
                <?php } ?>
                <!--- FIN VISTA MENU LATERAL ADMINISTRADOR --->

                <!--- INICIO VISTA MENU LATERAL CAJERO --->

                <?php if($_SESSION['cargo_svi']=="Cajero"){ ?>
                <!--<li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-briefcase fa-fw"></i> &nbsp; Administración <i class="fas fa-chevron-down"></i></a>
                    <ul>                        
                        <li>
                            <a href="<?php echo SERVERURL; ?>client-new/">
                                <i class="fas fa-child fa-fw"></i> &nbsp; Nuevo cliente
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-boxes fa-fw"></i> &nbsp; Productos <i class="fas fa-chevron-down"></i></a>
                    <ul>                        
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-list/">
                                <i class="fas fa-boxes fa-fw"></i> &nbsp; Productos en almacen
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-sold/">
                                <i class="fas fa-fire-alt fa-fw"></i> &nbsp; Lo más vendido
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-category/">
                                <i class="fab fa-shopify fa-fw"></i> &nbsp; Productos por categoría
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-expiration/">
                                <i class="fas fa-history fa-fw"></i> &nbsp; Productos por vencimiento
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-minimum/">
                                <i class="fas fa-stopwatch-20 fa-fw"></i> &nbsp; Productos en stock mínimo
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>product-search/">
                                <i class="fas fa-search fa-fw"></i> &nbsp; Buscar productos
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Ventas <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-new/">
                                <i class="fas fa-cart-plus fa-fw"></i> &nbsp; Nueva venta
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-new/wholesale/">
                                <i class="fas fa-parachute-box fa-fw"></i> &nbsp; Venta por mayoreo
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-list/">
                                <i class="fas fa-coins fa-fw"></i> &nbsp; Ventas realizadas
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-pending/">
                                <i class="fab fa-creative-commons-nc fa-fw"></i> &nbsp; Ventas pendientes
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-search-date/">
                                <i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar venta (Fecha)
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>sale-search-code/">
                                <i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar venta (Código)
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-wallet fa-fw"></i> &nbsp; Movimientos en cajas <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo SERVERURL; ?>movement-new/">
                                <i class="far fa-money-bill-alt fa-fw"></i> &nbsp; Nuevo movimiento
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>movement-list/">
                                <i class="fas fa-money-check-alt fa-fw"></i> &nbsp; Movimientos realizados
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SERVERURL; ?>movement-search/">
                                <i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar movimientos
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-cogs fa-fw"></i> &nbsp; Configuraciones <i class="fas fa-chevron-down"></i></a>
                    <ul>

                        <li>
                            <a href="<?php echo SERVERURL."user-update/".$lc->encryption($_SESSION['id_svi'])."/"; ?>">
                                <i class="fas fa-sync fa-fw"></i> &nbsp; Actualizar cuenta
                            </a>
                        </li>
                    </ul>
                </li>-->
                <?php } ?>
                <!--- FIN VISTA MENU LATERAL CAJERO --->
            </ul>
        </nav>
    </div>
</section>