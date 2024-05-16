<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
    </h3>
    <p class="text-justify">
        ¡hola Bienvenido <strong><?php echo $_SESSION['nombre_svi']." ".$_SESSION['apellido_svi']; ?></strong>
        ! Este es el panel principal del sistema acá podrá encontrar atajos para acceder a los procesos de cada obra activa del sistema.
    </p>
</div>




<div class="container-fluid">
    <div class="full-box tile-container">
    <!-- INICIO VISTA DASHBOARD ADMINISTRADOR -->
    
    <?php
        if($_SESSION['cargo_svi']=="Administrador"){

            require_once "./controladores/dashboardControlador.php";
            $ins_dashboard = new dashboardControlador();
            echo $ins_dashboard->mostrar_obras_controlador();
        }
    ?>
    
    <!-- FIN VISTA DASHBOARD ADMINISTRADOR -->

    <!-- INICIO VISTA DASHBOARD CAJERO -->
    
    <?php
        if($_SESSION['cargo_svi']=="Cajero"){
    ?>
   

    <?php } ?>
    <!-- INICIO VISTA DASHBOARD CAJEROR -->
    </div>
</div>

<br>

<section class="section dashboard">
      <div class="row">

        <!-- columna izquierda -->
        <div class="col-lg-8">
          <div class="row">

          <!-- cuadro registrados -->
          <div class="col-xxl-4 col-xl-4">
          <div class="card info-card customers-card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Registrados <span>| Total</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-card-list"></i>
                </div>
                <div class="ps-3">
                  <?php 
                      $gastos_total   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM gastos WHERE programaId IS NULL");
                      $total3 = $gastos_total->rowCount();
                  ?>
                  <h6><?php echo $total3;?></h6>
                  <span class="text-danger small pt-1 fw-bold">100%</span> <span class="text-muted small pt-2 ps-1">deuda</span>

                </div>
              </div>

            </div>
          </div>
          </div><!-- fin cuadro registrados -->
          
          <!-- cuadro programados -->
          <div class="col-xxl-4 col-md-4">
            <div class="card info-card revenue-card">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                  <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
              </div>

              <div class="card-body">
                <?php 
                  $programa = $lc->ejecutar_consulta_simple_publica("SELECT * FROM programas WHERE estado = 'abierto'");
                  $totalp = $programa->rowCount();
                  //$campo = $programa->fetch();
                  //$programaId = $campo['programaId'];
                ?>
                <h5 class="card-title">Programados <span>| <?php echo $totalp;?> Programas</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-card-checklist"></i>
                  </div>
                  <?php 
                      $gastos_pendientes   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM gastos WHERE programaId IS NOT NULL ");
                      $total2 = $gastos_pendientes->rowCount();
                      $porcentaje1 = ($total2 * 100)/$total3;
                  ?>
                  <div class="ps-3">
                    <h6><?php echo $total2; ?></h6>
                    <span class="text-success small pt-1 fw-bold"><?php echo number_format($porcentaje1,2,'.','');?>%</span> <span class="text-muted small pt-2 ps-1">en espera</span>

                  </div>
                </div>
              </div>

            </div>
          </div><!-- fin cuadro progrmados-->


            <!-- cuadro pagados -->
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                <?php $hoy = date('F, Y'); ?>
                  <h5 class="card-title">Pagados <span>| <?php echo $hoy;?></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <?php 
                        $gastos_pagados   =   $lc->datos_condicion1("gastos","estadoGasto","pagado");
                        $total = $gastos_pagados->rowCount();
                        $porcentaje2 = ($total * 100)/$total3;
                    ?>
                    <div class="ps-3">
                      <h6><?php echo $total;?></h6>
                      <span class="text-success small pt-1 fw-bold"><?php echo number_format($porcentaje2,2,'.','');?>%</span> <span class="text-muted small pt-2 ps-1">cancelado</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- fin cuadro pagados -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Reportes <span>/Hoy</span></h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <?php 
                    //gastos
                    $hoy = date('n');
                      $gastos   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM gastos WHERE MONTH(fechaHora) = $hoy");
                      $totas = $gastos->fetchAll();
                      $cart = array();
                      foreach($totas as $rows){
                        $cart[] = $rows['monto'];
                      }

                    //ingresos
                      $ingresos   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM ingresos");
                      $totai = $ingresos->fetchAll();
                      $ingr = array();
                      foreach($totai as $rowsi){
                        $ingr[] = $rowsi['monto'];
                      }

                    //programas
                      $programas   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM programas");
                      $totap = $programas->fetchAll();
                      $prog = array();
                      foreach($totap as $rowsp){
                        $prog[] = $rowsp['saldo'];
                      }
                      
                  ?>

                  <script>
                    //gastos
                    var arrayjs = <?php echo json_encode($cart); ?>;
                    var arr = Array.from(arrayjs);

                    //ingresos
                    var arrayjsi = <?php echo json_encode($ingr); ?>;
                    var arri = Array.from(arrayjsi);

                    //ingresos
                    var arrayjsp = <?php echo json_encode($prog); ?>;
                    var arrp = Array.from(arrayjsp);
                    
                    /*for(var i=0; i<arrayjs.length; i++){ 
                      
                      document.write(arrayjs[i]+",");
                    }*/

                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Gastos',
                          data: arr
                        }, {
                          name: 'Ingresos',
                          data: arri
                        }, {
                          name: 'saldo Programa',
                          data: arrp
                        }],
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: {
                            show: false
                          },
                        },
                        markers: {
                          size: 4
                        },
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth',
                          width: 2
                        },
                        xaxis: {
                          type: 'datetime',
                          categories: ["2018-09-19T08:00:00.000Z", "2018-09-19T09:30:00.000Z", "2018-09-19T10:30:00.000Z", "2018-09-19T11:30:00.000Z", 
                                        "2018-09-19T12:30:00.000Z", "2018-09-19T13:30:00.000Z", "2018-09-19T14:30:00.000Z", "2018-09-19T15:30:00.000Z",
                                        "2018-09-19T16:30:00.000Z", "2018-09-19T17:30:00.000Z", "2018-09-19T18:30:00.000Z", "2018-09-19T19:30:00.000Z"]
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM/yy HH:mm'
                          },
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Line Chart -->

                </div>

              </div>
            </div><!-- End Reports -->

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Detalle Programas <span>| Activos</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">Programa</th>
                        <th scope="col">Obra</th>
                        <th scope="col">Ingreso</th>
                        <th scope="col">Gastos</th>
                        <th scope="col">Pagos</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $programaA   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM programas WHERE estado = 'abierto'");
                        $datos = $programaA->fetchAll();
                        foreach($datos as $rows){
                      ?>
                        <tr>
                          <th scope="row"><a href="<?php echo SERVERURL; ?>programa-detalle/<?php echo $lc->encryption($rows['programaId'])?>/"><?php echo $rows['codigo'] ?></a></th>
                          <?php 
                            $obraId = $rows['obraId'];
                            $obraA   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM obras WHERE obraId = $obraId");
                            $datoO = $obraA->fetch();
                          ?>
                          <td><?php echo $datoO['abreviatura'] ; ?></td>
                          <?php 
                            $ingresoId = $rows['ingresoId'];
                            $ingresoA   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM ingresos WHERE ingresoId = $ingresoId");
                            $datoI = $ingresoA->fetch();
                          ?>
                          <td><?php echo $datoI['monto'] ; ?></td>
                          <?php 
                            $programaId = $rows['programaId'];
                            $gastoA   =   $lc->ejecutar_consulta_simple_publica("SELECT SUM(monto) as totalG FROM gastos WHERE programaId = $programaId");
                            $datoG = $gastoA->fetch();
                          ?>
                          <td><?php echo $datoG['totalG'] ; ?></td>
                          <?php 
                            $programaId = $rows['programaId'];
                            $gastoP   =   $lc->ejecutar_consulta_simple_publica("SELECT SUM(monto) as totalP FROM gastos WHERE programaId = $programaId AND estadoGasto IS NOT NULL");
                            $datoP = $gastoP->fetch();
                          ?>
                          <td><?php echo $datoP['totalP'] ; ?></td>
                        </tr>
                      <?php };?>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                        <td>$64</td>
                        <td class="fw-bold">124</td>
                        <td>$5,828</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                        <td>$46</td>
                        <td class="fw-bold">98</td>
                        <td>$4,508</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                        <td>$59</td>
                        <td class="fw-bold">74</td>
                        <td>$4,366</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                        <td>$32</td>
                        <td class="fw-bold">63</td>
                        <td>$2,016</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                        <td>$79</td>
                        <td class="fw-bold">41</td>
                        <td>$3,239</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>
        </div><!-- End Left side columns -->

        <!-- COLUMNA DEERECHA -->
        <div class="col-lg-4">

          <!-- PAGOS RECIENTES -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Pagos recientes <span>| Hoy</span></h5>
              <div class="activity">
                <?php 
                  $gastos_pagados   =   $lc->ejecutar_consulta_simple_publica("SELECT * FROM gastos WHERE estadoGasto = 'pagado' ORDER BY fechaHora DESC LIMIT 10");
                  $datos = $gastos_pagados->fetchAll();
                  foreach($datos as $rows){
                ?>
                  <!-- DESCRIPCION DE PAGO-->
                  <div class="activity-item d-flex">
                    <div class="activite-label"><?php echo $rows['fechaHora'] ?></div>
                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                    <div class="activity-content">
                      <!--Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae-->
                      S/. <?php echo $rows['monto'] ?> - <?php echo $rows['detalle'] ?>
                    </div>
                  </div><!-- FIN DE DESCRIPCION DE PAGO-->
                <?php } ?>
              </div>
            </div>
          </div><!-- FIN PAGOS RECIENTES -->

          <!-- Budget Report -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Allocated Budget', 'Actual Spending']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'Sales',
                          max: 6500
                        },
                        {
                          name: 'Administration',
                          max: 16000
                        },
                        {
                          name: 'Information Technology',
                          max: 30000
                        },
                        {
                          name: 'Customer Support',
                          max: 38000
                        },
                        {
                          name: 'Development',
                          max: 52000
                        },
                        {
                          name: 'Marketing',
                          max: 25000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Allocated Budget'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Actual Spending'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Montos Totales -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Montos Totales <span>| Hoy</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <!-- CONSULTAS -->
              <?php 
                $mesA = date('n');

                //$obraId = $rows['obraId'];
                $ingresosMT   =   $lc->ejecutar_consulta_simple_publica("SELECT SUM(monto) as totalMI FROM ingresos WHERE MONTH(fecha) >= $mesA-1");
                $datoIMT = $ingresosMT->fetch();
                $mTotalI = $datoIMT['totalMI'];
                
                echo $mTotalI;
              ?>

              <script>

                var mti = <?php echo $mTotalI;?>;
                
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: mti,
                          name: 'Ingresos'
                        },
                        {
                          value: 735,
                          name: 'Pagados'
                        },
                        {
                          value: 580,
                          name: 'Programados'
                        },
                        {
                          value: 484,
                          name: 'Registrados'
                        },
                        {
                          value: 300,
                          name: 'saldos'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- FIN Montos Totales -->

          <!-- News & Updates Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>
    </section>
