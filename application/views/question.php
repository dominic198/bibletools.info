<h2><?php echo $title; ?></h2>
<div class="question-verses">
	<?php foreach( $verses as $verse ) {
		echo "<a>{$verse['reference']}</a>";
	} ?>
</div>
<div id="resource_list" class="row">
	<div class="col-sm-8">
		<?php foreach( $resources as $resource ) { ?>
    		<div class="panel panel-modern">
				<div class="panel-heading">
					<img src="/assets/img/authors/egw.png"/>
					<div class="resource-info">
						<strong><?php echo $resource["name"]; ?></strong><br/>
						<small>Desire of Ages, p.55</small>
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
					<?php foreach( $related_questions as $question ) {
						echo "<li><a href='/question/{$question['slug']}'>{$question['title']}</a></li>";
					} ?>
				</ul>
			</div>
		</div><!--/ .panel -->
	</div>
</div><!--/ .row -->
