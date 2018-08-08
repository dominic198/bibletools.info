<h2 style="margin-bottom: 3rem">
	<a href="/<?php echo $navigation["prev"]; ?>" title="Previous Verse" class="ref-link prev-verse <?php echo $navigation["prev"] ? "" : "hidden"; ?>"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 50 400 400"><path fill="currentColor" d="M192 127.338v257.324c0 17.818-21.543 26.741-34.142 14.142L29.196 270.142c-7.81-7.81-7.81-20.474 0-28.284l128.662-128.662c12.599-12.6 34.142-3.676 34.142 14.142z" class=""></path></svg></a>
	<span class="text-ref"><?php echo $text_ref; ?></span>
	<a href="/<?php echo $navigation["next"]; ?>" title="Next Verse" class="ref-link next-verse <?php echo $navigation["next"] ? "" : "hidden"; ?>"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 50 400 400"><path fill="currentColor" d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z" class=""></path></svg></a>
</h2>
<div id="resource_list" class="row">
	<div class="col-sm-8 left-column">
		<div class="panel panel-modern verse" data-short-ref="<?php echo $short_ref; ?>" data-ref="<?php echo $text_ref; ?>">
			<div class="panel-heading text-center"><strong>King James Version (KJV)</strong></div>
			<div class="panel-body"><?php echo $verse; ?></div>
		</div><!--/ .panel -->
		<?php foreach( $main_resources as $resource ) { ?>
    		<div class="panel panel-modern resource" data-index-id="<?php echo $resource["id"]; ?>">
				<div class="panel-heading">
					<div class="author-icon <?php echo $resource["logo"]; ?>"></div>
					<div class="resource-info">
						<strong><?php echo $resource["author"]; ?></strong><br/>
						<small><?php echo $resource["source"]; ?></small>
					</div>
				</div>
				<div class="panel-body"><?php echo $resource["content"]; ?></div>
				<div class="panel-footer">
					<small>Was this helpful?</small>
					<a class="mark-unhelpful"></a>
					<a class="mark-helpful"></a>
				</div>
			</div><!--/ .panel -->
		<?php } ?>
	</div>
	<div class="col-sm-4 right-column">
		<?php foreach( $sidebar_resources as $resource ) { ?>
    		<div class="panel panel-modern resource <?php echo $resource["class"]; ?>">
				<div class="panel-heading">
					<strong><?php echo $resource["source"]; ?></strong>
				</div>
				<div class="panel-body">
					<?php echo $resource["content"]; ?>
				</div>
			</div><!--/ .panel -->
		<?php } ?>
	</div>
</div><!--/ .row -->