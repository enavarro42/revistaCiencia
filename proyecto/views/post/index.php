
<?php if(isset($this->posts) && count($this->posts)): ?>

<table class="table table-bordered">
    
    <?php for($i = 0; $i < count($this->posts); $i++): ?>
    
    <tr>
        <td><?php echo $this->posts[$i]['id_post']; ?></td>
        <td><?php echo $this->posts[$i]['titulo']; ?></td>
        <td><?php echo $this->posts[$i]['cuerpo']; ?></td>
        <td>
            <?php if(isset($this->posts[$i]['imagen'])): ?>
            
            <a href='<?php echo $_layoutParams['root'] . 'public/img/post/'. $this->posts[$i]['imagen']; ?>'>
                <img src='<?php echo $_layoutParams['root'] . 'public/img/post/thumb/thumb_'. $this->posts[$i]['imagen']; ?>' />
            </a>
            
            <?php endif; ?>
        </td>
        <td><a href="<?php echo BASE_URL . 'post/editar/' . $this->posts[$i]['id_post']; ?>">Editar</a></td>
        <td><a href="<?php echo BASE_URL . 'post/eliminar/' . $this->posts[$i]['id_post']; ?>">Eliminar</a></td>
    </tr>
    
    <?php endfor;?>
    
</table>
<?php else: ?>

<p><storng>No hay posts!</storng></p>

<?php endif; ?>

<?php if(isset($this->paginacion)) echo $this->paginacion;?>



<p><a href="<?php echo BASE_URL; ?>post/nuevo">Agregar post</a></p>


