<h2 style="margin-bottom: 3rem">Add Question</h2>
<div class="col-lg-10 m-auto">
	<div class="panel panel-modern">
		<div class="panel-body p-5">
			<form class="clearfix" action="/admin/add_question" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="title" placeholder="Title">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="slug" placeholder="URL slug">
				</div>
				<label>Resources</label>
				<div class="resources">
					<div class="card clearfix resource mb-3" style="background-color: #f9f9f9">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-3">
									<input type="number" class="form-control" name="resource_id[]" placeholder="Resource ID">
								</div>
								<div class="col-sm-1 text-center">
									or
								</div>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="reference[]" placeholder="EGW Reference">
								</div>
							</div><br/>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<textarea name="snippet[]" class="form-control" placeholder="Snippet"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm add-resource mt-2"> + Add Resource</a>
				<br/><br/>
				<label>Associated Verses</label>
				<div class="verses">
					<div class="card clearfix verse mb-3" style="background-color: #f9f9f9">
						<div class="card-body">
							<div class="form-group">
								<input type="text" class="form-control verse-reference" name="verse_reference[]" placeholder="Reference">
								<input type="hidden" class="start" name="start[]">
								<input type="hidden" class="end" name="end[]">
							</div>
						</div>
					</div>
				</div>
				<a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm add-verse mt-2"> + Add Verse</a>
				<br/><br/>
				<div class="form-group">
					<label>Category</label>
					<select name="category_id" class="form-control">
						<?php foreach( $categories as $category ) {
							echo "<option value='{$category['id']}'>{$category['name']}</option>";
						} ?>
					</select>
				</div>
				<button type="submit" class="btn btn-primary btn-lg">Save Question</button>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="/assets/js/en_bcv_parser.min.js"></script>
<script>
$( document ).ready( function() {
	$( ".add-resource" ).click( function() {
		$clone = $( ".resource" ).clone();
		$clone.find( "input, textarea" ).val( "" );
		$clone.appendTo( ".resources" );
	});
	$( ".add-verse" ).click( function() {
		$clone = $( ".verse" ).clone();
		$clone.find( "input" ).val( "" );
		$clone.appendTo( ".verses" );
	});
	$( document ).on( "blur", ".verse-reference", function() {
		var bcv = new bcv_parser;
		bcv.set_options( { book_alone_strategy: "first_chapter" } );
		ref = bcv.parse( $(this).val() ).osis();
		ref = ref.split( "-" );
		$( this ).parent().find( ".start" ).val( ref[0] );
		if( ref[1] ) {
			$( this ).parent().find( ".end" ).val( ref[1] );
		}
	});
	$( "input[name='title']" ).on( "change keyup", function() {
		$( "input[name='slug']" ).val( slugify( $(this).val() ) );
	});
	
	function slugify( text )
	{
		return text.toString().toLowerCase()
			.replace(/\s+/g, '-')
			.replace(/[^\w\-]+/g, '')
			.replace(/\-\-+/g, '-')
			.replace(/^-+/, '')
			.replace(/-+$/, '');
	}
});
</script>