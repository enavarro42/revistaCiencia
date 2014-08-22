
    <div>
        <div class="center-block">
            <h1 class="text-center login-title">Iniciar sesi&oacute;n</h1>
            <div class="account-wall">
                <img class="profile-img" src="<?php echo BASE_URL;?>image/user.png"
                    alt="">
                <form class="form-signin" name='form1' method='post' action=''>
                    <input type="hidden" value="1" name="enviar" />

                    <input type="text" class="form-control" placeholder="Usuario" required autofocus name="usuario" value="<?php if(isset($this->datos)) echo $this->datos['usuario']; ?>">

                    <input type="password" name="pass" class="form-control" placeholder="Contrase&ntilde;a" required>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar sesi&oacute;n</button>

                    <a href="#" class="pull-left need-help">Olvid&oacute; su contrase&ntilde;a? </a><span class="clearfix"></span>
                </form>
            </div>
            <a href="<?php echo BASE_URL; ?>registro" class="text-center new-account">Crear una cuenta</a>
        </div>
    </div>