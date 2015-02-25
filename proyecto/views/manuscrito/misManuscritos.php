


<h3>Mis Manuscritos</h3>
<?php if(isset($this->enlaceCorreccion)){ ?>
  <form action="<?php echo $this->enlaceCorreccion; ?>" method="POST">

    <input type="hidden" name="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">
    <input type="hidden" name="id_revision" value="<?php echo $this->id_revision; ?>">
    <input type="hidden" name="id_estatus" value="<?php echo $this->id_estatus; ?>">

    <button type="submit" class="btn btn-info pull-right"><span class="glyphicon glyphicon-wrench icon_gly"></span> Corregir Manuscrito</button>

  </form>
<?php } ?>

<div class="clearfix visible-lg-block"></div>

<div class="table-responsive margin-top5">
  <table class="table table-bordered">
      
      
      <thead>
        <!--<th>#</th>-->
        <!--<th>Revista</th>-->
        <th>T&iacute;tulo</th>
        <!--<th>Autores</th>-->
        <th>Por</th>
        <th>Estatus</th>
        <th>Fecha</th>
        <th>Detalles</th>
      </thead>
      
       
      
      <tbody>
          
          <?php for($i = 0; $i < count($this->manuscritos); $i++): ?>
          
          <tr <?php if($i == 0) echo 'class="success"'; ?>>
              <!--<td><?php // echo $this->manuscritos[$i]['id_manuscrito']; ?></td>-->
              <!--<td><?php // echo $this->manuscritos[$i]['nombre']; ?></td>-->
              <td><?php echo $this->manuscritos[$i]['titulo']; ?></td>
              <td>
                  <select id="autor" name="autor" class="form-control">
                    <?php for($j = 0; $j <count($this->autores[$this->manuscritos[$i]['id_manuscrito']]); $j++): ?>
                      <option value="">
                          <?php  echo $this->autores[$this->manuscritos[$i]['id_manuscrito']][$j]['persona_'.$j]; ?>
                      </option>
                    <?php  endfor; ?>
                  </select>
              </td>
        
              <!-- <td><?php echo $this->manuscritos[$i]['rol']; ?></td> -->
              <td><?php echo $this->manuscritos[$i]['estatus']; ?></td>
              <td><?php echo $this->manuscritos[$i]['fecha']; ?></td>
              <td><a href="<?php echo $this->enlaceDetalles . $this->manuscritos[$i]['id_revision'] . '/' . $this->id_manuscrito; ?>" class="btn btn-info">Detalles</a></td>
          </tr>
          
          <?php endfor; ?>
          
      </tbody>
      
  </table>
    
</div>


<ul class="pagination">
    <?php if(isset($this->paginacion)) echo $this->paginacion;?>
</ul>