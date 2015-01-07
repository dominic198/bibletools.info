<!----------TEMPLATES---------->
<script id="bible_template" type="text/x-jquery-tmpl">
<div class="verse" id="${verse}">
	<p><strong>${verse} </strong>${text}</p>
</div>
</script>
<script id="egw_template" type="text/x-jquery-tmpl">
<div class="col-sm-6 box egw" data-reference="${reference}">
	<div class="panel panel-modern">
		<div class="panel-heading">
			<span class="title">
				{{if verse}}
					${chapter}:${verse}{{if endverse}}-${endverse}{{/if}} <span>${reference}</span>
				{{else}}
					<span>${reference}</span>
				{{/if}}
			</span>
			<a href="http://text.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=${reference}" title="Open at EGWWritings.org" target="_blank" class="fa fa-share-square-o open"></a>
		</div>
		<div class="panel-body">
			loading ...
		</div>
	</div><!--/ .panel -->
</div><!--/ .col -->
</script>
<script id="bc_template" type="text/x-jquery-tmpl">
<div class="col-sm-6 box bc">
	<div class="panel panel-modern">
		<div class="panel-heading">Bible Commentary</div>
		<div class="panel-body">
			{{html content}}
		</div>
	</div><!--/ .panel -->
</div>
</script>
<script id="verse_template" type="text/x-jquery-tmpl">
{{if strongs}}
	<span data-strongs="${strongs}">${word}</span>
{{else}}
	${word}
{{/if}}
</script>
<div id="headerwrap">
    <div class="container">
    	<div class="row centered">
    		<div class="col-lg-12">
				<h1><b>BibleTools</b>.info</h1>
				<h3>Bible verse resources and analysis tools.</h3>	
				<form action="." id="search_form">			
					<select id="tokenize" multiple="multiple" class="tokenize-sample">
					    <option selected="selected" value="1" data-chapters="50">Genesis</option>
					    <option selected="selected" value="1:1">1:1</option>
						<option value="3" data-chapters="27">Leviticus</option>
						<option value="4" data-chapters="36">Numbers</option>
						<option value="5" data-chapters="34">Deuteronomy</option>
						<option value="6" data-chapters="24">Joshua</option>
						<option value="7" data-chapters="21">Judges</option>
						<option value="8" data-chapters="4">Ruth</option>
						<option value="9" data-chapters="31">1 Samuel</option>
						<option value="10" data-chapters="24">2 Samuel</option>
						<option value="11" data-chapters="22">1 Kings</option>
						<option value="12" data-chapters="25">2 Kings</option>
						<option value="13" data-chapters="29">1 Chronicles</option>
						<option value="14" data-chapters="36">2 Chronicles</option>
						<option value="15" data-chapters="10">Ezra</option>
						<option value="16" data-chapters="13">Nehemiah</option>
						<option value="17" data-chapters="10">Esther</option>
						<option value="18" data-chapters="42">Job</option>
						<option value="19" data-chapters="150">Psalms</option>
						<option value="20" data-chapters="31">Proverbs</option>
						<option value="21" data-chapters="12">Ecclesiastes</option>
						<option value="22" data-chapters="8">Song of Solomon</option>
						<option value="23" data-chapters="66">Isaiah</option>
						<option value="24" data-chapters="52">Jeremiah</option>
						<option value="25" data-chapters="5">Lamentations</option>
						<option value="26" data-chapters="48">Ezekiel</option>
						<option value="27" data-chapters="12">Daniel</option>
						<option value="28" data-chapters="14">Hosea</option>
						<option value="29" data-chapters="3">Joel</option>
						<option value="30" data-chapters="9">Amos</option>
						<option value="31" data-chapters="1">Obadiah</option>
						<option value="32" data-chapters="4">Jonah</option>
						<option value="33" data-chapters="7">Micah</option>
						<option value="34" data-chapters="3">Nahum</option>
						<option value="35" data-chapters="3">Habakkuk</option>
						<option value="36" data-chapters="3">Zephaniah</option>
						<option value="37" data-chapters="2">Haggai</option>
						<option value="38" data-chapters="14">Zechariah</option>
						<option value="39" data-chapters="4">Malachi</option>
						<option value="40" data-chapters="28">Matthew</option>
						<option value="41" data-chapters="16">Mark</option>
						<option value="42" data-chapters="24">Luke</option>
						<option value="43" data-chapters="21">John</option>
						<option value="44" data-chapters="28">Acts</option>
						<option value="45" data-chapters="16">Romans</option>
						<option value="46" data-chapters="16">1 Corinthians</option>
						<option value="47" data-chapters="13">2 Corinthians</option>
						<option value="48" data-chapters="6">Galatians</option>
						<option value="49" data-chapters="6">Ephesians</option>
						<option value="50" data-chapters="4">Philippians</option>
						<option value="51" data-chapters="4">Colossians</option>
						<option value="52" data-chapters="5">1 Thessalonians</option>
						<option value="53" data-chapters="3">2 Thessalonians</option>
						<option value="54" data-chapters="6">1 Timothy</option>
						<option value="55" data-chapters="4">2 Timothy</option>
						<option value="56" data-chapters="3">Titus</option>
						<option value="57" data-chapters="1">Philemon</option>
						<option value="58" data-chapters="13">Hebrews</option>
						<option value="59" data-chapters="5">James</option>
						<option value="60" data-chapters="5">1 Peter</option>
						<option value="61" data-chapters="3">2 Peter</option>
						<option value="62" data-chapters="5">1 John</option>
						<option value="63" data-chapters="1">2 John</option>
						<option value="64" data-chapters="1">3 John</option>
						<option value="65" data-chapters="1">Jude</option>
						<option value="66" data-chapters="22">Revelation</option>
					</select>
				</form>
				<br>
    		</div>
    	</div>
    </div> <!--/ .container -->
</div><!--/ #headerwrap -->
<div class="container main">
	<div class="row">
    		<div id="resource_list">
    			<div class="col-sm-6" id="verse">
		    		<div class="panel panel-modern">
						<div class="panel-heading">Loading...</div>
						<div class="panel-body">
							Loading...
						</div>
					</div><!--/ .panel -->
		    	</div>
    		</div>
    		<a id="load_more">Load More</a>
	</div><!--/ .row -->
</div>
