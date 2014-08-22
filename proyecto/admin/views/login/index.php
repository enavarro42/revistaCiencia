<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php if(isset($this->titulo)) echo $this->titulo; ?></title>
<!--CSS-->
    <link href='<?php echo $_layoutParams['ruta_css'];?>bootstrap.min.css' rel='stylesheet' type='text/css'>
    <link href="<?php echo $_layoutParams['ruta_css'];?>font-awesome.min.css" rel="stylesheet"  type="text/css" />
    <link href="<?php echo $_layoutParams['ruta_css'];?>estilo-admin.css" rel="stylesheet"  type="text/css" />


    <?php if(isset($_layoutParams['cssPublic']) && count($_layoutParams['cssPublic'])): ?>
    <?php for($i=0; $i < count($_layoutParams['cssPublic']); $i++): ?>
    
    <link href="<?php echo $_layoutParams['cssPublic'][$i] ?>" rel="stylesheet"  type="text/css" />
    
    <?php endfor; ?>
    <?php endif; ?>

    <script src="<?php echo BASE_URL;?>public/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/jquery.metisMenu.js"></script>
    <script src="<?php echo BASE_URL;?>public/js/sb-admin.js"></script>
    
<!--JS publicos -->
    
    <?php if(isset($_layoutParams['jsPublic']) && count($_layoutParams['jsPublic'])): ?>
    <?php for($i=0; $i < count($_layoutParams['jsPublic']); $i++): ?>
    
    <script src="<?php echo $_layoutParams['jsPublic'][$i] ?>" type="text/javascript"></script>
    
    <?php endfor; ?>
    <?php endif; ?>
    
    
    <!--JS privados de cada vista-->
    <?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
    <?php for($i=0; $i < count($_layoutParams['js']); $i++): ?>
    
    <script src="<?php echo $_layoutParams['js'][$i] ?>" type="text/javascript"></script>
    
    <?php endfor; ?>
    <?php endif; ?>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-sign-in fa-fw"></i> Iniciar sesi&oacute;n</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method='post' action=''>
                            <input type="hidden" value="1" name="enviar" />
                            <fieldset>
                                <label class="error"><?php echo $this->_error; ?></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Usuario" required autofocus name="usuario" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control"  name="pass" class="form-control" placeholder="Contrase&ntilde;a" required>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button class="btn btn-lg btn-success btn-block" type="submit">Iniciar sesi&oacute;n</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>