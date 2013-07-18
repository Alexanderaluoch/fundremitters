<?php $this->load->view('client/includes/header'); ?>
	
	<div class="row-fluid login-wrapper">
	   <hr/>
        <div class="span4 box well">
            <div class="content-wrap">
                <?=form_open('client/company/login');?>
		         <?//=$cookie_info= array();
		         //$cookie_info=$this->ezauth->fetch_userinfo();?>
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
            </div>
        </div>
    </div>

<?php $this->load->view('client/includes/footer'); ?>
