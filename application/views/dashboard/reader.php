<!----------TEMPLATES---------->
<script id="bible_template" type="text/x-jquery-tmpl">
<div class="verse" id="${verse}">
	<p><strong>${verse} </strong>${text}</p>
</div>
</script>
<script id="egw_template" type="text/x-jquery-tmpl">
<div class="box egw small" ref="${reference}">
	<h4 class="title">
	{{if verse}}
		${chapter}:${verse}{{if endverse}}-${endverse}{{/if}} <span>${reference}</span>
	{{else}}
		<span>${reference}</span>
	{{/if}}
	<a href="http://text.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=${reference}" title="Open at EGWWritings.org" target="_blank" class="fa fa-share-square-o open"></a>
	</h4>
	<div class="content">loading ...</div>
</div>
</script>
<script id="bc_template" type="text/x-jquery-tmpl">
<div class="box bc small" ref="${reference}">
	<h4 class="title">SDA Bible Commentary</h4>
	<div class="content">{{html content}}</div>
</div>
</script>
<script>
$(document).ready(function(){
	$.getJSON("/kjv/get/1/1/", function(data) {
		$("#bible_template").tmpl(data).appendTo("#reader #chapterContent");
	});
	
	$.getJSON("/egw/get/genesis/1/", function(data) {
		$("#egw_template").tmpl(data).appendTo("#col-right");
		loadEGWContent();
	});
	
	$("#load_more").click(function(){
		
		$(this).html("loading...");
		var verse = $(".verse.selected").attr("id");
		var book = $("#book h2 .bookName").text();
		var book = book.replace(/\s+/g, '');
		var chapter = $("#chapter .chapter").text();
		var offset = $(".egw.small").length;
					
		if(!isNaN(verse)){
		
			$.getJSON("/egw/get_from_verse/" + book + "/" + chapter + "/" + verse + "/" + offset, function(data) {
				$("#egw_template").tmpl(data).appendTo("#col-right");
				loadEGWContent();
				$("#load_more").html("Load More");
			});
		
		} else {
			$.getJSON("/egw/get/" + book + "/" + chapter + "/" + offset, function(data) {
				$("#egw_template").tmpl(data).appendTo("#col-right");
				loadEGWContent();
				$("#load_more").html("Load More");
			});
		}
	});
});
function loadVerse(book, chapter, verse, offset){
	//
}
</script>
<div id="col-left" class="lg study">
	<div id="browser">
		<div class="toolbar">
			<div class="inside">
				<dl id="book" class="dropdown">
        			<dt><a><h2><span class="book"><span class="bookName">Genesis</span><span class="value">1</span></span><span class="more"></span></h2></a></dt>
       				<dd>
        				<div class="wrap">
        					<span class="arrow"></span>
				            <ul> 
				            	<h3>Book</h3>
				                <li><a data-chapters="50"><span class="bookName">Genesis</span><span class="value">1</span></a></li>
								<li><a data-chapters="40"><span class="bookName">Exodus</span><span class="value">2</span></a></li>
								<li><a data-chapters="27"><span class="bookName">Leviticus</span><span class="value">3</span></a></li>
								<li><a data-chapters="36"><span class="bookName">Numbers</span><span class="value">4</span></a></li>
								<li><a data-chapters="34"><span class="bookName">Deuteronomy</span><span class="value">5</span></a></li>
								<li><a data-chapters="24"><span class="bookName">Joshua</span><span class="value">6</span></a></li>
								<li><a data-chapters="21"><span class="bookName">Judges</span><span class="value">7</span></a></li>
								<li><a data-chapters="4"><span class="bookName">Ruth</span><span class="value">8</span></a></li>
								<li><a data-chapters="31"><span class="bookName">1 Samuel</span><span class="value">9</span></a></li>
								<li><a data-chapters="24"><span class="bookName">2 Samuel</span><span class="value">10</span></a></li>
								<li><a data-chapters="22"><span class="bookName">1 Kings</span><span class="value">11</span></a></li>
								<li><a data-chapters="25"><span class="bookName">2 Kings</span><span class="value">12</span></a></li>
								<li><a data-chapters="29"><span class="bookName">1 Chronicles</span><span class="value">13</span></a></li>
								<li><a data-chapters="36"><span class="bookName">2 Chronicles</span><span class="value">14</span></a></li>
								<li><a data-chapters="10"><span class="bookName">Ezra</span><span class="value">15</span></a></li>
								<li><a data-chapters="13"><span class="bookName">Nehemiah</span><span class="value">16</span></a></li>
								<li><a data-chapters="10"><span class="bookName">Esther</span><span class="value">17</span></a></li>
								<li><a data-chapters="42"><span class="bookName">Job</span><span class="value">18</span></a></li>
								<li><a data-chapters="150"><span class="bookName">Psalms</span><span class="value">19</span></a></li>
								<li><a data-chapters="31"><span class="bookName">Proverbs</span><span class="value">20</span></a></li>
								<li><a data-chapters="12"><span class="bookName">Ecclesiastes</span><span class="value">21</span></a></li>
								<li><a data-chapters="8"><span class="bookName">Song of Solomon</span><span class="value">22</span></a></li>
								<li><a data-chapters="66"><span class="bookName">Isaiah</span><span class="value">23</span></a></li>
								<li><a data-chapters="52"><span class="bookName">Jeremiah</span><span class="value">24</span></a></li>
								<li><a data-chapters="5"><span class="bookName">Lamentations</span><span class="value">25</span></a></li>
								<li><a data-chapters="48"><span class="bookName">Ezekiel</span><span class="value">26</span></a></li>
								<li><a data-chapters="12"><span class="bookName">Daniel</span><span class="value">27</span></a></li>
								<li><a data-chapters="14"><span class="bookName">Hosea</span><span class="value">28</span></a></li>
								<li><a data-chapters="3"><span class="bookName">Joel</span><span class="value">29</span></a></li>
								<li><a data-chapters="9"><span class="bookName">Amos</span><span class="value">30</span></a></li>
								<li><a data-chapters="1"><span class="bookName">Obadiah</span><span class="value">31</span></a></li>
								<li><a data-chapters="4"><span class="bookName">Jonah</span><span class="value">32</span></a></li>
								<li><a data-chapters="7"><span class="bookName">Micah</span><span class="value">33</span></a></li>
								<li><a data-chapters="3"><span class="bookName">Nahum</span><span class="value">34</span></a></li>
								<li><a data-chapters="3"><span class="bookName">Habakkuk</span><span class="value">35</span></a></li>
								<li><a data-chapters="3"><span class="bookName">Zephaniah</span><span class="value">36</span></a></li>
								<li><a data-chapters="2"><span class="bookName">Haggai</span><span class="value">37</span></a></li>
								<li><a data-chapters="14"><span class="bookName">Zechariah</span><span class="value">38</span></a></li>
								<li><a data-chapters="4"><span class="bookName">Malachi</span><span class="value">39</span></a></li>
								<li><a data-chapters="28"><span class="bookName">Matthew</span><span class="value">40</span></a></li>
								<li><a data-chapters="16"><span class="bookName">Mark</span><span class="value">41</span></a></li>
								<li><a data-chapters="24"><span class="bookName">Luke</span><span class="value">42</span></a></li>
								<li><a data-chapters="21"><span class="bookName">John</span><span class="value">43</span></a></li>
								<li><a data-chapters="28"><span class="bookName">Acts</span><span class="value">44</span></a></li>
								<li><a data-chapters="16"><span class="bookName">Romans</span><span class="value">45</span></a></li>
								<li><a data-chapters="16"><span class="bookName">1 Corinthians</span><span class="value">46</span></a></li>
								<li><a data-chapters="13"><span class="bookName">2 Corinthians</span><span class="value">47</span></a></li>
								<li><a data-chapters="6"><span class="bookName">Galatians</span><span class="value">48</span></a></li>
								<li><a data-chapters="6"><span class="bookName">Ephesians</span><span class="value">49</span></a></li>
								<li><a data-chapters="4"><span class="bookName">Philippians</span><span class="value">50</span></a></li>
								<li><a data-chapters="4"><span class="bookName">Colossians</span><span class="value">51</span></a></li>
								<li><a data-chapters="5"><span class="bookName">1 Thessalonians</span><span class="value">52</span></a></li>
								<li><a data-chapters="3"><span class="bookName">2 Thessalonians</span><span class="value">53</span></a></li>
								<li><a data-chapters="6"><span class="bookName">1 Timothy</span><span class="value">54</span></a></li>
								<li><a data-chapters="4"><span class="bookName">2 Timothy</span><span class="value">55</span></a></li>
								<li><a data-chapters="3"><span class="bookName">Titus</span><span class="value">56</span></a></li>
								<li><a data-chapters="1"><span class="bookName">Philemon</span><span class="value">57</span></a></li>
								<li><a data-chapters="13"><span class="bookName">Hebrews</span><span class="value">58</span></a></li>
								<li><a data-chapters="5"><span class="bookName">James</span><span class="value">59</span></a></li>
								<li><a data-chapters="5"><span class="bookName">1 Peter</span><span class="value">60</span></a></li>
								<li><a data-chapters="3"><span class="bookName">2 Peter</span><span class="value">61</span></a></li>
								<li><a data-chapters="5"><span class="bookName">1 John</span><span class="value">62</span></a></li>
								<li><a data-chapters="1"><span class="bookName">2 John</span><span class="value">63</span></a></li>
								<li><a data-chapters="1"><span class="bookName">3 John</span><span class="value">64</span></a></li>
								<li><a data-chapters="1"><span class="bookName">Jude</span><span class="value">65</span></a></li>
								<li><a data-chapters="22"><span class="bookName">Revelation</span><span class="value">66</span></a></li>
				
				            </ul>
            			</div>
        			</dd>
    			</dl>
    
			    <dl id="chapter" class="dropdown">
			        <dt><a><h2><span class="chapter">1</span><span class="more"></span></h2></a></dt>
			        <dd>
			        	<div class="wrap">
			        		<span class="arrow"></span>
				            <ul>
				            	<h3>Chapter</h3>
				                <li><a>1</a></li>
				                <li><a>2</a></li>
				                <li><a>3</a></li>
				                <li><a>4</a></li>
				                <li><a>5</a></li>
				                <li><a>6</a></li>
				                <li><a>7</a></li>
				                <li><a>9</a></li>
				                <li><a>10</a></li>
				                <li><a>11</a></li>
				                <li><a>12</a></li>
				                <li><a>13</a></li>
				                <li><a>14</a></li>
				                <li><a>15</a></li>
				                <li><a>16</a></li>
				                <li><a>17</a></li>
				                <li><a>18</a></li>
				                <li><a>19</a></li>
				                <li><a>20</a></li>
				                <li><a>21</a></li>
				                <li><a>22</a></li>
				                <li><a>23</a></li>
				                <li><a>24</a></li>
				                <li><a>25</a></li>
				                <li><a>26</a></li>
				                <li><a>27</a></li>
				                <li><a>28</a></li>
				                <li><a>29</a></li>
				                <li><a>30</a></li>
				                <li><a>31</a></li>
				                <li><a>32</a></li>
				                <li><a>33</a></li>
				                <li><a>34</a></li>
				                <li><a>35</a></li>
				                <li><a>36</a></li>
				                <li><a>37</a></li>
				                <li><a>38</a></li>
				                <li><a>39</a></li>
				                <li><a>40</a></li>
				                <li><a>41</a></li>
				                <li><a>42</a></li>
				                <li><a>43</a></li>
				                <li><a>44</a></li>
				                <li><a>45</a></li>
				                <li><a>46</a></li>
				                <li><a>47</a></li>
				                <li><a>48</a></li>
				                <li><a>49</a></li>
				                <li><a>50</a></li>
				            </ul>
			            </div>
			        </dd>
			    </dl>
			</div>
		</div>
		<div id="reader" class="col left">
			<div class="loader"></div>
			<div class="sub_toolbar"></div>
    
		<div id="chapterContent"></div>
		</div>
	</div><!--END #BROWSER -->
</div><!--End col-left-->
<div id="col-right">
	<a id="load_more">Load More</a>	
</div><!--END #col-right-->