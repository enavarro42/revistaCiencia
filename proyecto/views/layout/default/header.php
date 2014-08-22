<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <!--<meta name="viewport" content="width=device-width, maximun-scale=1"/>-->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title><?php if(isset($this->titulo)) echo $this->titulo; ?></title>
    <!--CSS-->
        <link href='<?php echo $_layoutParams['ruta_css'];?>bootstrap.min.css' rel='stylesheet' type='text/css'>
        <link href="<?php echo $_layoutParams['ruta_css'];?>estilo.css" rel="stylesheet"  type="text/css" />
        
        
        <?php if(isset($_layoutParams['cssPublic']) && count($_layoutParams['cssPublic'])): ?>
        <?php for($i=0; $i < count($_layoutParams['cssPublic']); $i++): ?>
        
        <link href="<?php echo $_layoutParams['cssPublic'][$i] ?>" rel="stylesheet"  type="text/css" />
        
        <?php endfor; ?>
        <?php endif; ?>
        
        <!--JavaScript-->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->

        <script src="<?php echo BASE_URL;?>public/js/jquery-1.10.2.min.js"></script>
        <script src="<?php echo BASE_URL;?>public/js/bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL;?>public/js/resize.js"></script>
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
        

        

        <script>
            //var BASE_URL = "http://localhost/revistaciencia/";
            var BASE_URL = "http://localhost/revista/proyecto/";
            //var BASE_URL = "http://e-navarro.com.ve/";
            var entro = new Array();
            entro[0] = 0;
            
            $(window).resize(function(){
                var elem = $(this);
            
                // Update the info div width and height - replace this with your own code
                // to do something useful!
                /*alert('window width: ' + elem.width() + ', height: ' + elem.height() );*/
                menu = $('#menu_horizontal').find('ul');

                if(elem.width() > 768){
                    entro[0] = 0;
                    cargarMenu();
                    
                    menu.removeClass('open-menu');
                }else{
                    var ajax = new XMLHttpRequest(); 
                    ajax.addEventListener("load", completeHandler, false); 
                    ajax.addEventListener("error", errorHandler, false); 
                    ajax.open("POST", BASE_URL + "login/usuario_logeado"); 
                    ajax.send();
                }
            });
            
            function cargarMenu(){
                menu = $('#menu_horizontal').find('ul');
                $(menu).empty();
                
                var url = new Array();
                url[0] = BASE_URL;
                url[1] = BASE_URL + "revistas";
                url[2] = BASE_URL;
                url[3] = BASE_URL;
                url[4] = BASE_URL;
                url[5] = BASE_URL;

                var text = new Array();
                text[0] = "Inicio";
                text[1] = "Revistas";
                text[2] = "Buscar";
                text[3] = "Actual";
                text[4] = "Archivos";
                text[5] = "Contacto";

                for(i=0; i<url.length; i++){
                    $("<li><a href='"+url[i]+"'>"+text[i]+"</a></li>").appendTo(menu);
                }
            }
            
            function errorHandler(event){
                alert("errorHandler: problemas de consulta..."); 
            } 
            function completeHandler(event){
                if(parseInt(event.target.responseText) && entro[0] == 0){
                    entro[0] = 1;
                    var url = new Array();
                    url[0] = BASE_URL + "manuscrito";
                    url[1] = BASE_URL + "manuscrito/nuevo";
                    url[2] = BASE_URL + "articulo";
                    url[3] = BASE_URL + "usuario";
                    url[4] = BASE_URL + "perfil";
                    
                    var text = new Array();
                    text[0] = "Mis Manuscritos";
                    text[1] = "Nuevo Manuscrito";
                    text[2] = "Mis Art&aacute;culos";
                    text[3] = "Usuario";
                    text[4] = "Perfil";
                    
                    menu = $('#menu_horizontal').find('ul');
                    
                    for(i=0; i<url.length; i++){
                        $("<li><a href='"+url[i]+"'>"+text[i]+"</a></li>").appendTo(menu);
                    }
                }
                
                if(!parseInt(event.target.responseText)){
                    entro[0] = 0;
                    cargarMenu();
                }
            } 

            $(function() {
             
                var btn_movil = $('#nav-mobile'),
                menu = $('#menu_horizontal').find('ul');
             
                // Al dar click agregar/quitar clases que permiten el despliegue del menú
                btn_movil.on('click', function (e) {
                    e.preventDefault();
             
                    var el = $(this);
             
                    el.toggleClass('nav-active');
                    menu.toggleClass('open-menu');
                });
            });

        </script>
</head>

<body>

<div class="visible-lg">lg</div>
<div class="visible-md">md</div>
<div class="visible-sm">sm</div>
<div class="visible-xs">xs</div>
    
<div id="bar-top">
    <div id="usuario">
        <ul class="navTop">
            
 
              <?php if(isset($_layoutParams['menu_top'])): ?>
                 <?php for($i = 0; $i < count($_layoutParams['menu_top']); $i++): ?>
                    <?php if(Session::get('autenticado') && $i == 0): ?>
                        <!-- <li></li> -->
                        <li><a href="<?php echo $_layoutParams['menu_top'][$i]['enlace']?>" class="text_user">
                            <!-- <img src="<?php echo BASE_URL;?>public/img/usuario/user.png" alt="imagen" style="width: 20px; height: 20px; display:inline;" class="img-circle"> -->
                            <span class="<?php echo $_layoutParams['menu_top'][$i]['icon']?>"></span>
                            <?php echo $_layoutParams['menu_top'][$i]['titulo']?></a>
                        </li>
                        <!-- <li><a href="#">Messages <span class="badge">0</span></a></li> -->
                    <?php else: ?>
                        <li>
                            <a href="<?php echo $_layoutParams['menu_top'][$i]['enlace']?>">
                            <span class="<?php echo $_layoutParams['menu_top'][$i]['icon']?>"></span>
                            <?php echo $_layoutParams['menu_top'][$i]['titulo']?>
                            </a>
                        </li>
                    <?php endif; ?>
                 <?php endfor; ?>
             <?php endif; ?>
                    
             
        </ul>
    </div>
</div>

<div id="contenedor">
        <header class="grid-header">

                <div id="logoLuz" class=""><!--class="col-3-12"-->
                        <img src="<?php echo $_layoutParams['ruta_img'];?>logoLuz.png" alt="LUZ"><p class="text_logo_luz"><span>UNIVERSIDAD</span></br><span id="delZulia">DEL ZULIA</span></p>
                </div>

                <div id="logo" class="">
                    <img src="<?php echo $_layoutParams['ruta_img'];?>logoRevista.png" class="logoRevista1" alt="Revistas FEC">
                    <img src="<?php echo $_layoutParams['ruta_img'];?>logoRevista2.png" class="logoRevista2" alt="Revistas FEC">
                    <!--<p><a href="">Revistas</a><a href="">Arbitradas</a></p>-->
                </div>
                
                <div class="caja_search">
                    <div>
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Buscar aqu&iacute;...">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Buscar</button>
                          </span>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-6 -->
                </div>

        </header>

        <nav id="menu_horizontal">
            <a class="nav-mobile" id="nav-mobile" href="#"></a>
            <ul>
                <?php if(isset($_layoutParams['menu_horizontal'])): ?>
                <?php for($i = 0; $i < count($_layoutParams['menu_horizontal']); $i++): ?>
                <li><a href="<?php echo $_layoutParams['menu_horizontal'][$i]['enlace']?>"><?php echo $_layoutParams['menu_horizontal'][$i]['titulo']?></a></li>
                <?php endfor; ?>
                <?php endif; ?>
            </ul>
        </nav>
    
        <section id="caja_contenido">

                <aside id="panel_left">
                        <h5><strong>REVISTAS ARBITRADAS</strong></h5>
                        <nav class="menu_vertical">
                                <ul>
                                    <?php if(isset($_layoutParams['menu_left'])): ?>
                                        <?php for($i = 0; $i < count($_layoutParams['menu_left']); $i++): ?>
                                            <?php if(isset($_SESSION['vista_actual'])): ?>
                                                <?php if(Session::get('vista_actual') == $_layoutParams['menu_left'][$i]['id']): ?>
                                                    <li class="current"><a  href="<?php echo $_layoutParams['menu_left'][$i]['enlace']?>"><?php echo $_layoutParams['menu_left'][$i]['titulo']?></a></li>
                                                <?php else: ?>
                                                    <li><a href="<?php echo $_layoutParams['menu_left'][$i]['enlace']?>"><?php echo $_layoutParams['menu_left'][$i]['titulo']?></a></li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </ul>
                        </nav>
                        
                        <h5><strong>REVISTAS</strong></h5>
                        <nav class="menu_vertical">
                                <ul>
                                        <li><a href="<?php echo BASE_URL .'revistas/info/Ciencias'; ?>">Ciencias</a></li>
                                        <li><a href="">Opci&oacute;n</a></li>
                                        <li><a href="">Divulgaciones Matem&aacute;ticas</a></li>
                                        <li><a href="">Enl@ce</a></li>
                                        <li><a href="">Anartia</a></li>
                                </ul>
                        </nav>
                </aside>

            <section id="contenido">
                <div id="pad_content">
                    <noscript><p>Para el correcto funcionamiento de la p&acaute;gina de tener habilitado JavaScript..!</p></noscript>
                    <?php if(isset($this->_error)): ?>
                    <div id="error"><?php echo $this->_error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($this->_mensaje)): ?>
                    <div id="mensaje"><?php echo $this->_mensaje; ?></div>
                    <?php endif; ?>
