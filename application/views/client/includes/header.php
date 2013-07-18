<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <title>FUND &middot; REMITTERS</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Fund-remitters is a service which enables PesaPal to Mobile Money cash transfers..">
      <meta name="author" content="SunSmart Solutions">

    <!-- Le styles -->
    <link href="<?php echo base_url()?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url()?>bootstrap/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>font-awesome/css/font-awesome.min.css">

    <link href="<?php echo base_url() ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url() ?>js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url() ?>ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url() ?>ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url() ?>ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url() ?>ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="<?php echo base_url() ?>ico/favicon.png">
  </head>

  <body>
      <header>
          <div class="row top">
              <div class="span3 logo">
                  <a class="brand" href="<?php echo base_url()?>"><img src="<?php echo base_url()?>img/Logo/fundremitters.png"></a>
              </div>
              
              <?php
              $segment = $this->uri->segment(3);
              $segment_userpage = $this->uri->segment(1);
              if ($segment=="userpage" || $segment_userpage=="userpage"){
              //Only Shows in a Logged In user Pages 
               ?>
              <div class="span8 sign-in">
                    <div class="user-actions row pull-right">
                      <div class="span2"><strong> Welcome: &nbsp</strong><a href="#">Tom Kimani</a></div>
                      <div class="span2"><a href=""><i class="icon-off icon-large"></i>&nbsp Logout</a></div>
                    </div>
              </div>

              <?php }//Only Show in the Register Page
              else if ($segment == 'register'){
              ?>
              <div class="span8 sign-in">
                   <a class="btn btn-primary pull-right" href="<?php echo base_url();?>/index.php/client/company/login">SIGN IN</a>
                   <strong class="pull-right">Already have an account &nbsp </strong>
              </div>


              <?php }
              //Only Show in the Log In Page
              else if ($segment=='login'){
               ?>
              <div class="span8 sign-in">
                  <a class="btn btn-primary pull-right" href="<?php echo base_url();?>/index.php/client/company/register">SIGN UP</a>
                  <strong class="pull-right">Don't have an Account? &nbsp </strong>
              </div> 
               
               <?php }else{
                //Only Show in the website home pages
                ?>
                <div class="span5">
                 <ul class="nav nav-pills pull-right">
                  <li class="active"><a href="<?php echo site_url('client/home') ?>">Home</a></li>
                  <li><a href="<?php echo site_url('client/company') ?>">How It Works</a></li>
                  <li><a href="<?php echo site_url('client/company') ?>">Pricing</a></li>
                  <li><a href="<?php echo site_url('client/company/faqs') ?>">FAQs</a></li>
                </ul>
               </div>

               <div class="span3 sign-in">
                   <a class="btn btn-primary pull-right" href="<?php echo base_url();?>/index.php/client/company/login">SIGN IN</a>
              </div>
              <?php }?>

          </div>
      </header>


