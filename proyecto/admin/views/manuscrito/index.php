<h3>Mis Manuscritos</h3>

<div class="table-responsive">
  <?php 
  if(isset($this->manuscritos)){
  ?>
  <table class="table table-bordered">
      
      
      <thead>
        <th>Nro</th>
        <th>T&iacute;tulo</th>
      </thead>
      
      <tbody>
          
          <?php 
          
            for($i = 0; $i < count($this->manuscritos); $i++){
          ?>
          
          <tr>
              <td><?php echo $this->manuscritos[$i]['id_manuscrito']; ?></td>
              <td><a href="<?php echo BASE_URL . 'manuscrito/misManuscritos/'. 1 .'/'. $this->manuscritos[$i]['id_manuscrito'];?>"><?php echo $this->manuscritos[$i]['titulo']; ?></a></td>
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
    





