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
        <script src="<?php echo BASE_URL;?>public/js/jquery.validate.js"></script>
        
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

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">REVISTAS ARBITRADAS</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['user']; ?>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
<!--                         <li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['user']; ?></a>
                        </li>
                        <li class="divider"></li> -->
                        <li><a href="<?php echo BASE_URL . 'login/cerrar' ?>"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesi&oacute;n</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>

                        <?php if(isset($_layoutParams['menu_principal'])): ?>
                        <?php for($i = 0; $i < count($_layoutParams['menu_principal']); $i++): ?>
                        <li>
                            <a href="<?php echo $_layoutParams['menu_principal'][$i]['enlace']?>"><i class="fa fa-dashboard fa-fw"></i> <?php echo $_layoutParams['menu_principal'][$i]['titulo']?>
                                <?php if($_layoutParams['menu_principal'][$i]['flecha']) echo '<span class="fa arrow"></span>'; ?>
                            </a>
                            
                            <?php 
                                if(isset($_layoutParams['sub_menu_' . $_layoutParams['menu_principal'][$i]['id']])){ 
                            ?>
                            <ul class="nav nav-second-level">
                                <?php for($j = 0; $j < count($_layoutParams['sub_menu_' . $_layoutParams['menu_principal'][$i]['id']]); $j++){ ?>
                                <li>
                                    <a href="<?php echo $_layoutParams['sub_menu_' . $_layoutParams['menu_principal'][$i]['id']][$j]['enlace']?>"><?php echo $_layoutParams['sub_menu_' . $_layoutParams['menu_principal'][$i]['id']][$j]['titulo']?></a>
                                </li>
                                <?php } ?>
                            </ul>
                            <?php } ?>

                        </li>
                        <?php endfor; ?>
                        <?php endif; ?>

                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>