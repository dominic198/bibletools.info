<h2 style="margin-bottom: 3rem">
	<a href="/<?php echo $navigation["prev"]; ?>" title="Previous Verse" class="fa fa-caret-left ref-link prev-verse <?php echo $navigation["prev"] ? "" : "hidden"; ?>"></a>
	<span class="text-ref"><?php echo $text_ref; ?></span>
	<a href="/<?php echo $navigation["next"]; ?>" title="Next Verse" class="fa fa-caret-right ref-link next-verse <?php echo $navigation["next"] ? "" : "hidden"; ?>"></a>
</h2>
<div id="resource_list" class="row">
	<div class="col-sm-8 left-column">
		<div class="panel panel-modern verse" data-short-ref="<?php echo $short_ref; ?>" data-ref="<?php echo $text_ref; ?>">
			<div class="panel-heading text-center"><strong>King James Version (KJV)</strong></div>
			<div class="panel-body"><?php echo $verse; ?></div>
		</div><!--/ .panel -->
		<?php foreach( $main_resources as $resource ) { ?>
    		<div class="panel panel-modern resource">
				<div class="panel-heading">
					<div class="author-icon <?php echo $resource["logo"]; ?>"></div>
					<div class="resource-info">
						<strong><?php echo $resource["author"]; ?></strong><br/>
						<small><?php echo $resource["source"]; ?></small>
					</div>
				</div>
				<div class="panel-body"><?php echo $resource["content"]; ?></div>
			</div><!--/ .panel -->
		<?php } ?>
	</div>
	<div class="col-sm-4 right-column">
		<?php foreach( $sidebar_resources as $resource ) { ?>
    		<div class="panel panel-modern resource <?php echo $resource["class"]; ?>">
				<div class="panel-heading">
					<strong><?php echo $resource["name"]; ?></strong>
				</div>
				<div class="panel-body">
					<?php echo $resource["content"]; ?>
				</div>
			</div><!--/ .panel -->
		<?php } ?>
	</div>
</div><!--/ .row -->