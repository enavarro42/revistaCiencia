<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><?php echo $this->titulo; ?></h2>

            <div class="reportes col-md-4 col-lg-4">
              <img src="<?php echo $_layoutParams['ruta_img'] . '/list.png'; ?>" alt="..." class="img-rounded">
              <p>Reportes de Usuarios<br /></p>
              <p><a href="<?php echo $this->urlReporteUsuario; ?>" class="btn btn-danger">Generar Reporte</a></p>
            </div>

            <div class="reportes col-md-4 col-lg-4">
              <img src="<?php echo $_layoutParams['ruta_img'] . '/chart1.png'; ?>" alt="..." class="img-rounded">
              <p>Reportes de Usuarios y Manuscritos</p>
              <p><a href="<?php echo $this->urlReporteTotalUsuario; ?>" class="btn btn-danger">Generar Reporte</a></p>
            </div>

            <div class="reportes col-md-4 col-lg-4">
              <img src="<?php echo $_layoutParams['ruta_img'] . '/table.png'; ?>" alt="..." class="img-rounded">
              <p>Reportes de Usuarios y Manuscritos</p>
              <p><a href="<?php echo $this->urlReporteManuscrito; ?>" class="btn btn-danger">Generar Reporte</a></p>
            </div>

        </div>


    </div>
</div>