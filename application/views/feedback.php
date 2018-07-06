<h2 style="margin-bottom: 3rem">Feedback</h2>
<div class="col-lg-10 m-auto">
	<div class="panel panel-modern">
		<div class="panel-body p-5">
			<p>Questions, suggestions, bug reports, ideas, we want to hear them! Since this is a side project, I can't guarantee a response right away, but we'll do our best.</p>
			<form class="clearfix" id="contact" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="name" placeholder="Name">
				</div>
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="Email">
				</div>
				<div class="form-group">
					<textarea name="message" class="form-control" placeholder="Message"></textarea>
				</div>
				<button type="submit" id="submit" class="btn btn-primary btn-lg">Send Feedback</button>
			</form>
		</div>
	</div>
</div>
<script>
$( document ).on( "submit", "form#contact", function(e) {
	e.preventDefault();
	if($(this).find( "#message" ) != "" ){
		data = $(this).serializeArray();
		btn =  $(this).find( "#submit" );
		btnText = btn.text();
		btn.text( "Sending feedback..." );
		$.post( "/about/send_feedback", data, function(){
			$( "body" ).prepend('<div class="alert global error alert-success" role="alert"></span>Feedback sent successfully</div>');
			$( ".global.error" ).delay(4000).fadeOut(2000);
			$('form#contact').trigger( "reset" );
			btn.text(btnText);
		});
	}
});
</script>