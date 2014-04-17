<script type="text/javascript">
	$( document ).ready(function() {
		$("#login-form").submit(function() {
			$.noty.closeAll();
			username=$("input[name='login']").val();
			password=$("input[name='password']").val();
			$.ajax({
				type: "POST",
				url: "/login.php",
				data: "username="+username+"&pwd="+password,
				success: function(html) {  
					$("#loading").hide();
					if(html=='true')    {
						noty({
							type: 'success',
							timeout: 5000,
							text: 'Login Successful!'
						});
						$("#include-content").load("include/control_feeder.php");
					} else {
						noty({
							type: 'error',
							timeout: 5000,
							text: 'There was an error with your E-Mail/Password combination. Please try again.'
						});
					}
				},
				beforeSend:function() {
					$("#loading").show;
				}
			});
			
			return false;
		});
	});
</script>

<form name="login-form" id="login-form" method="post">
    <h1><span class="log-in">Log in</span></h1>
    <p class="float">
        <label for="login"><i class="icon-user"></i>Username</label>
        <input type="text" name="login" placeholder="Username">
    </p>
    <p class="float">
        <label for="password"><i class="icon-lock"></i>Password</label>
        <input type="password" name="password" placeholder="Password">
    </p>
    <p class="clearfix">
        <input type="submit" name="submit" class="button gold-button" value="Log in">
    </p>
</form>​​