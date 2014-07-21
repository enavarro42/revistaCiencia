<?php if(isset($this->_paginacion)): ?>

<?php if($this->_paginacion['primero']): ?>
    <li><a href="<?php echo $link . $this->_paginacion['primero']; ?>">Primero</a></li>
    
<?php else: ?>
    
    <li  class="disabled"><a href="">Primero</a></li>
    
<?php endif; ?>
    
&nbsp;


<?php if($this->_paginacion['anterior']): ?>
    <li><a href="<?php echo $link . $this->_paginacion['anterior']; ?>">&laquo;</a></li>
    
<?php else: ?>
    
    <li class="disabled"><a href="" disabled>&laquo;</a></li>
    
<?php endif; ?>
    
 &nbsp;
 
 <?php for($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>
 
    <?php if($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
        
        <li class="active"><a href="" disabled><?php echo $this->_paginacion['rango'][$i]; ?></a></li>

    <?php else: ?>

 <li><a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>">
            <?php echo $this->_paginacion['rango'][$i]; ?>
        </a>
 </li>
    <?php endif; ?>
    
<?php endfor; ?>
 
 &nbsp;
 
<?php if($this->_paginacion['siguiente']): ?>
    <li><a href="<?php echo $link . $this->_paginacion['siguiente']; ?>">&raquo;</a></li>
    
<?php else: ?>
    
    <li class="disabled"><a href="" disabled>&raquo;</a></li>
    
<?php endif; ?>
    
 &nbsp;
 
<?php if($this->_paginacion['ultimo']): ?>
    <li><a href="<?php echo $link . $this->_paginacion['ultimo']; ?>">&Uacute;ltimo</a></li>
    
<?php else: ?>
    
    <li class="disabled"><a href="" disabled>&Uacute;ltimo</a></li>
    
<?php endif; ?>
    
<?php endif; ?>


