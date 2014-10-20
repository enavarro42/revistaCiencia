<h3>Solicitud de Arbitraje</h3>

<?php if(isset($this->solicitud) && $this->solicitud['estatus'] == -1){ ?>

<?php $arreglo = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'); ?>


  	<p class="text-right">Maracaibo, <?php echo date("d");  ?> de <?php echo $arreglo[date("n")];  ?> de <?php echo date("Y");  ?></p>


<p>Ciudadano<br />
<?php echo $this->persona['primerNombre'] ." " . $this->persona['apellido']; ?><br />
Presente.-<br />
</p>
 
 
<p>La Revista CIENCIA adscrita a la Facultad de Ciencias de la Universidad del Zulia, Maracaibo Venezuela se complace en invitarle a participar como árbitro de nuestra revista y de ser su gusto, solicitarle la revisión del manuscrito titulado:</p>

<strong><?php echo $this->manuscrito['titulo']; ?></strong><br /><br />

<p>Le agradecería hacer las observaciones pertinentes y devolverlo en un plazo no mayor de 15 días, contados a partir de la fecha de recibo. Así mismo, estimaríamos altamente que de no ser posible su revisión, lo notifique a traves del de las opciones mostradas al final o nos sugiera algún especialista del área que pudiera evaluarlo.</p>
 
<p>Agradeciéndole la colaboración que pueda prestarnos le saluda,</p>
  

<p>Atentamente,</p>
                                                      
 
<p>Dra. Marynes Montiel<br />
Editora Ejecutiva<br />
Revista CIENCIA<br />
</p>

<input type="hidden" name="id_manuscrito" id="id_manuscrito" value="<?php echo $this->id_manuscrito; ?>">
<input type="hidden" name="id_persona" id="id_persona" value="<?php echo $this->id_persona; ?>">

<div class="text-center">
	<h4>Arbitrar el manuscrito</h4>

	<label class="radio-inline">
	  <input type="radio" name="opcion" id="si" value="1"> Si
	</label>
	<label class="radio-inline">
	  <input type="radio" name="opcion" id="no" value="0"> No
	</label>

	<p></p>
	<button type="button" class="btn btn-primary" id="btn_aceptar">Aceptar</button>
</div>

<?php } else if(isset($this->solicitud) && $this->solicitud['estatus'] == 0){ ?>

	<p>La solicitud de arbitrar el manuscrito fu&eacute; Rechazada.</p>

<?php } else if(isset($this->solicitud) && $this->solicitud['estatus'] > 0){ ?>

	<p>La solicitud de arbitrar el manuscrito ya fu&eacute; Aceptada.</p>

<?php }else{ ?>
	<p>Url no encontrada.</p>
<?php } ?>