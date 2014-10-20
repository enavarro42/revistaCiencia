<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><?php echo $this->titulo; ?>
              <a href="" class="btn btn-danger pull-right sp_left">Eliminar</a>
              <a href="<?php echo BASE_URL ?>manuscrito/crear" class="btn btn-success pull-right sp_left">Crear</a>
            </h2>

            <?php if(isset($this->manuscritos) && $this->manuscritos){ ?>

            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th><input type="checkbox" name="checkAll" id="checkAll" value=""></th>
                    <th>ID</th>
                    <th>T&iacute;tulo</th>
                    <th>Revista</th>
                    <th>Autores</th>
                    <th>&Aacute;rbitros</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Editar</th>
                    <th>Descargar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for($i=0; $i<count($this->manuscritos); $i++){ ?>
                  <tr>
                    <td><input type="checkbox" name="check" class="case" value=""></td>
                    <td><?php echo $this->manuscritos[$i]['id_manuscrito']; ?></td>
                    <td><?php echo trim($this->manuscritos[$i]['titulo']); ?></td>
                    <td><?php echo trim($this->manuscritos[$i]['nombre']); ?></td>
                    <td>
                      <select name="" class="form-control-inline">
                        <?php for($j=0; $j<count($this->manuscritos[$i]['autores']); $j++){ ?>
                        <option value=""><?php echo $this->manuscritos[$i]['autores'][$j]; ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <!-- arbritros -->
                    <td><a href="<?php echo BASE_URL . 'manuscrito/arbitros/' .$this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-warning center-block"><span class="fa fa-gavel fa-fw"></span></a></td> 
                    <td><?php echo trim($this->manuscritos[$i]['fecha']); ?></td>
                    <td><label><?php echo trim($this->manuscritos[$i]['estatus']); ?></label></td>
                    <td><a href="<?php echo BASE_URL . 'manuscrito/editar/' .$this->manuscritos[$i]['id_manuscrito']; ?>" class="btn btn-info center-block"><span class="fa fa-pencil-square-o"></span></a></td>
                    <td><a href="" class="btn btn-success center-block"><span class="fa fa-cloud-download"></span></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>


            <ul class="pagination">
                <?php if(isset($this->manuscritos) && isset($this->paginacion)) echo $this->paginacion;?>
            </ul>

            <?php }else{ ?>
              <h4>
                <?php echo  $this->sin_manuscritos; ?>
              </h4>

            <?php } ?>

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

    





