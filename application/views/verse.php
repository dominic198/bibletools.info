<h2 style="margin-bottom: 3rem"><?php echo $text_ref; ?></h2>
<div id="resource_list" class="row">
	<div class="col-sm-8">
		<div class="panel panel-modern verse" data-short-ref="<?php echo $short_ref; ?>" data-ref="<?php echo $text_ref; ?>">
			<div class="panel-heading text-center">
				<?php echo $text_ref; ?><a href="/<?php echo $navigation["next"]; ?>" title="Next Verse" class="fa fa-caret-right next"></a><a href="/<?php echo $navigation["prev"]; ?>" title="Previous Verse" class="fa fa-caret-left prev"></a>
			</div>
			<div class="panel-body"><?php echo $verse; ?></div>
		</div><!--/ .panel -->
		<?php foreach( $resources as $resource ) { ?>
    		<div class="panel panel-modern resource">
				<div class="panel-heading">
					<img src="/assets/img/authors/<?php echo $resource["logo"]; ?>.png"/>
					<div class="resource-info">
						<strong><?php echo $resource["author"]; ?></strong><br/>
						<small><?php echo $resource["name"]; echo $resource["page_ref"] ? ", page " . $resource["page_ref"] : ""; ?></small>
					</div>
				</div>
				<div class="panel-body"><?php echo $resource["content"]; ?></div>
			</div><!--/ .panel -->
		<?php } ?>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-modern">
			<div class="panel-heading">
				<h3>Related Questions</h3>
			</div>
			<div class="panel-body">
				<ul>
				</ul>
			</div>
		</div><!--/ .panel -->
	</div>
</div><!--/ .row -->
<script>
$( document ).ready( function() {
	text_ref = $( "#verse" ).attr( "data-ref" );
	short_ref = $( "#verse" ).attr( "data-short-ref" );
	var ref_history = JSON.parse( localStorage.getItem( "history" ) );
	if( ! ref_history ) {
		ref_history = [];
	}
	if( ref_history[0] != ref ) {
		ref_history.unshift( ref );
		ref_history = ref_history.slice( 0, 10 );
		localStorage.setItem( "history", JSON.stringify( ref_history ) );
	}
});
</script>