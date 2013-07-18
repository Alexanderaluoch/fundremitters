<? include('includes/header.php'); ?>

<p>
To change your password, please type in your old password and your new password.
</p>

<?=$this->validation->error_string?>

<?=form_open('mystore/'.$this->uri->segment(2).'/'.$this->uri->segment(3));?>

<p>
Old Password:<br />
<?=form_input('old_password', $this->validation->old_password)?>
</p>

<p>
New Password:<br />
<?=form_input('new_password', $this->validation->new_password)?>
</p>

<p>
Confirm New Password:<br />
<?=form_input('new_password2', $this->validation->new_password2)?>
</p>


<p>
<input type="submit" value="Change Password" />
</p>

</form>

<? include('includes/footer.php'); ?>