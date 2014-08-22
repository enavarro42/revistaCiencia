<h3>Revistas</h3>
<div class="list-group">
<?php for($i = 0; $i < count($this->revistas); $i++): ?>
    <a href="<?php echo BASE_URL .'revistas/info/'.$this->revistas[$i]['nombre']; ?>" class="list-group-item"><?php echo $this->revistas[$i]['nombre']; ?></a>
<?php endfor; ?>
</div>