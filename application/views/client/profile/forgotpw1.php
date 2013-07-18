<? include('top.php'); ?>

<p>
To get a reset code sent to your e-mail address, please provide your user name or e-mail address.
</p>

<?=$this->validation->error_string?>

<?=form_open('mystore/'.$this->uri->segment(2).'/'.$this->uri->segment(3));?>

<p>
User name:<br />
<?=form_input('username', $this->validation->username)?>
</p>

<p>
or
</p>

<p>
E-mail address:<br />
<?=form_input('email', $this->validation->email)?>
</p>

<p>
<input type="submit" value="Send Reset Code" />
</p>

</form>


<? include('bottom.php'); ?>