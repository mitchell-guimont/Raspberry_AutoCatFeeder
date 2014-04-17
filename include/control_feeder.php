<script type="text/javascript">
	function callFeedCat(feedType) {
		$('input:button').not(".button-ignore").attr("disabled", true);
		
		message = "Unknown feed type";
		
		if (feedType == 'feed-early')
			message = "Are you sure you want to feed the cat early?";
		else if (feedType == 'feed-extra')
			message = "Are you sure you want to give the cat an extra feed?";
		
		$.noty.closeAll();
		noty({
			text: message,
			buttons: [{addClass: 'btn btn-primary', text: 'Yes', onClick: function($noty) {
				$noty.close();
				$.ajax({
					type: "POST",
					url: "/feed_cat.php",
					data: "feed_type="+feedType,
					success: function(html) {
						$('input:button').not(".button-ignore").attr("disabled", false);
						noty({
							type: 'Success',
							timeout: 5000,
							text: 'Feed Successful!'
						});
					}
				});
			  }
			},
			{addClass: 'btn btn-danger', text: 'No', onClick: function($noty) {
				$noty.close();
				$('input:button').not(".button-ignore").attr("disabled", false);
			  }
			}]
		});
	}

	$( document ).ready(function() {
		var showHistory = 0;
		
		$("#feed-early").click(function() {
			callFeedCat('feed-early');
		});
		
		$("#feed-extra").click(function() {
			callFeedCat('feed-extra');
		});
		
		$("#show-history").click(function() {
			if (showHistory == 0) {
				showHistory = 1;
				$.ajax({
					type: "POST",
					url: "/feed_history.php",
					success: function(html) {
						$("#feed_history").html(html);
					},
					beforeSend:function() {
						$("#feed_history").html('<div id="loading" align="center"><img border="0" src="images/ajax-loader.gif" /></div>');
						$("#feed_history").show();
					}
				});
			} else {
				showHistory = 0;
				$("#feed_history").html("");
				$("#feed_history").hide();
			}
		});
		
		$("#log-off").click(function() {
			$.noty.closeAll();
			$.ajax({
				type: "POST",
				url: "/logoff.php",
				success: function(html) {    
					$("#loading").hide();
					noty({
						type: 'warning',
						timeout: 5000,
						text: 'Logout Successful!'
					});
					$("#include-content").load("include/login_form.php");
				},
				beforeSend:function() {
					$("#include-content").html("");
					$("#loading").show();
				}
			});
		});
	});
</script>

<p class="clearfix">
	<input type="button" class="button button-fill" name="feed-early" id="feed-early" value="Feed Early" />
</p>
<p class="clearfix" style="margin-top:3px;">
	<input type="button" class="button button-fill" name="feed-extra" id="feed-extra" value="+ Extra Feeding" />
</p>
<p class="clearfix" style="margin-top:3px;">
	<input type="button" class="button button-fill button-ignore" name="show-history" id="show-history" value="Show History" />
    <ul id="feed_history" style="display:none;"></ul>
</p>
<p class="clearfix" style="margin-top:3px;">
	<input type="button" class="button button-fill button-ignore log-off-button" name="log-off" id="log-off" value="Log off" />
</p>