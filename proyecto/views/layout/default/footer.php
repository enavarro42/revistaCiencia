                    </div>
                </section>
                <aside id="panel_right">
                    <?php if(Session::get('autenticado')): ?>
                        <h5><strong>GESTIONAR</strong></h5>
                        <nav class="menu_vertical">
                            <ul>        
                                <?php if(isset($_layoutParams['menu_right'])): ?>
                                    <?php for($i = 0; $i < count($_layoutParams['menu_right']); $i++): ?>
                                        <?php if(isset($_SESSION['vista_actual'])): ?>
                                            <?php if(Session::get('vista_actual') == $_layoutParams['menu_right'][$i]['id']): ?>
                                                <li class="current"><a  href="<?php echo $_layoutParams['menu_right'][$i]['enlace']?>"><?php echo $_layoutParams['menu_right'][$i]['titulo']?></a></li>
                                            <?php else: ?>
                                                <li><a href="<?php echo $_layoutParams['menu_right'][$i]['enlace']?>"><?php echo $_layoutParams['menu_right'][$i]['titulo']?></a></li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </ul>
                        </nav>
                     <?php endif; ?>
                    
                    
                   <a class="twitter-timeline"  href="https://twitter.com/RevistasFEC"  data-widget-id="442840222066167808">Tweets por @RevistasFEC</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </aside>
        </section>
        <div class="corte"></div>
</div>
 
<footer>&copy; 2013 <?php echo APP_COMPANY;?>. Derechos reservados. Maracaibo, Venezuela. Acerca de <a href="#"><?php echo APP_NAME; ?></a></footer>
</body>
</html>
