<?php $this->load->view('client/includes/header'); ?>
	
	<div class="row-fluid login-wrapper">
	   <hr/>
        <div class="span4 box well">
            <div class="content-wrap">
                <form action="login" method="POST">
                <?php $cookie_info= array();
                  $cookie_info=$this->ezauth->fetch_userinfo();
                  if(isset($data_error)){echo $data_error;}
                  ?>
                <div class="log"><strong>LOGIN</strong></div>
                <?php if (isset($error_string)){echo $error_string;}?>
                <input class="span12" type="text" placeholder="Email Address" name="username">
                <input class="span12" type="password" placeholder="Your password" name="password">
                <a href="#" class="forgot">Forgot password?</a>
                <div class="remember">
                    <input id="remember-me" type="checkbox">
                    <label for="remember-me">stay signed In</label>
                </div>
                <button class="btn btn-primary login" type="submit">Log in</button>
                </form>
            </div>
        </div>
    </div>

<?php $this->load->view('client/includes/footer'); ?>
