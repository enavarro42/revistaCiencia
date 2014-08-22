<h3>Seleccionar Perfil</h3>
<div class="list-group">
<?php 
    $levels = Session::get('levels');
    for($i = 0; $i < count($levels); $i++):
?>

	<a href="<?php echo BASE_URL . trim($levels[$i]); ?>" class="list-group-item"><?php echo $levels[$i];  ?></a>


    <?php endfor; ?>
</div>
