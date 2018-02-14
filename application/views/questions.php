<h2 style="margin-bottom: 3rem">Questions</h2>
<div class="row">
	<div class="col-sm-8">
		<div class="panel panel-modern question-list">
			<div class="panel-body">
				<?php foreach( $formatted_questions as $category => $questions ) {
					echo "<h3>$category</h3>";
					echo "<ul>";
					foreach( $questions as $question ) {
						echo "<li><a href='/question/{$question['slug']}'>{$question['title']}</a></li>";
					}
					echo "</ul>";
				} ?>
			</div>
		</div><!--/ .panel -->
	</div>
    <div class="col-sm-4">
    	<div class="panel panel-modern">
			<div class="panel-heading">
				<h3>Popular</h3>
			</div>
			<div class="panel-body">
				<ul>
					<ul>
						<?php foreach( $popular_questions as $question ) {
							echo "<li><a href='/question/{$question['slug']}'>{$question['title']}</a></li>";
						} ?>
					</ul>
				</ul>
			</div>
		</div><!--/ .panel -->
		<div class="panel panel-modern">
			<div class="panel-heading">
				<h3>Recently Answered</h3>
			</div>
			<div class="panel-body">
				<ul>
					<ul>
						<?php foreach( $recent_questions as $question ) {
							echo "<li><a href='/question/{$question['slug']}'>{$question['title']}</a></li>";
						} ?>
					</ul>
				</ul>
			</div>
		</div><!--/ .panel -->
    </div>	
</div>