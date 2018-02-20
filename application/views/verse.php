<h2 style="margin-bottom: 3rem"><?php echo $text_ref; ?></h2>
<div id="resource_list" class="row">
	<div class="col-sm-8">
		<div class="panel panel-modern verse">
			<div class="panel-heading text-center">
				<?php echo $text_ref; ?><a title="Next Verse" class="fa fa-caret-right next pull-right"></a><a title="Previous Verse" class="fa fa-caret-left prev pull-left"></a>
			</div>
			<div class="panel-body"><?php echo $verse; ?></div>
		</div><!--/ .panel -->
		<?php foreach( $resources as $resource ) { ?>
    		<div class="panel panel-modern resource">
				<div class="panel-heading">
					<img src="/assets/img/authors/<?php echo $resource["logo"]; ?>.png"/>
					<div class="resource-info">
						<strong><?php echo $resource["author"]; ?></strong><br/>
						<small><?php echo $resource["name"]; ?></small>
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
