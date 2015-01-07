
//---------------------------------
//----------FORMATTING
//---------------------------------
$(document).ready(function(){
	$('body #container').delay(600).fadeIn(1000);
	
	$(document).ajaxError(function(e, jqxhr, settings, exception) {
	  $("body").prepend("<div class='global error'>Oops. Something bad just happened :(</div>");
	  $(".global.error").delay(4000).fadeOut(2000);
	});
	
	$(".loader").hide();
	
});

//---------------------------------
//----------BROWSER
//---------------------------------

$(document).ready(function(){

	$("#reader .verse").live("click", function(e) {
			
		$(".verse").removeClass("selected");
		$(this).addClass("selected");
		verse = $(this).attr("id");
		book = $("#book h2 .bookName").text();
		var book = book.replace(/\s+/g, '');
		chapter = $("#chapter .chapter").text();
		
		getVerse(book, chapter, verse);
	});


	$("#col-right .box").live( "click", function() {
		$(this).toggleClass("expand");
	});
	$("#col-right .box.expand p").live( "click", function() {
		return false;
	});
});

function getVerse(book, chapter, verse){
	$("#col-right .egw").remove();
		
	$("html, body").animate({ scrollTop: 0 }, 2000);
	
	$.getJSON("/sdabc/get/" + book + "/" + chapter + "/" + verse, function(data) {
		$("#bc_template").tmpl(data).prependTo("#col-right");
		loadEGWContent();
	});
	
	$("#col-right .bc").remove();
	$.getJSON("/egw/get_from_verse/" + book + "/" + chapter + "/" + verse, function(data) {
		$("#egw_template").tmpl(data).appendTo("#col-right");
		loadEGWContent();
	});
}

function getChapter(book, chapter){
	var book = book.replace(/\s+/g, '');
	$("#reader #chapterContent").empty();
	$("#col-right .box").remove();
	$("#reader .loader").show();
	$.getJSON("/kjv/get/" + book + "/" + chapter + "/", function(data) {
		$("#reader .loader").hide();
		$("#bible_template").tmpl(data).appendTo("#reader #chapterContent");
		bookname = getBookName(book);
		var bookname = bookname.replace(/\s+/g, '');
		$.getJSON("/egw/get/" + bookname + "/" + chapter + "/", function(data) {
			$("#egw_template").tmpl(data).appendTo("#col-right");
			loadEGWContent();
		});
	});
}

function loadEGWContent(){
	
	var ajax = new Array();
	$(".egw").each(function(){
	var egw_item = $(this);
		var ref = encodeURIComponent($(this).attr("ref"));
		if(ref !== "undefined"){
			$.get("/egw/content/" + ref, function(data) {
				//$("#details .verse").empty();
				
				var data = $.parseJSON(data);
				
				egw_item.find("div.content").empty().append(data.content);
				egw_item.find("h4 span").empty().append(data.title);
			});
		}
	});
}

function getBookName(bookID){
var books = [];
	books[1] = 'Genesis';
	books[2] = 'Exodus';
	books[3] = 'Leviticus';
	books[4] = 'Numbers';
	books[5] = 'Deuteronomy';
	books[6] = 'Joshua';
	books[7] = 'Judges';
	books[8] = 'Ruth';
	books[9] = '1 Samuel';
	books[10] = '2 Samuel';
	books[11] = '1 Kings';
	books[12] = '2 Kings';
	books[13] = '1 Chronicles';
	books[14] = '2 Chronicles';
	books[15] = 'Ezra';
	books[16] = 'Nehemiah';
	books[17] = 'Esther';
	books[18] = 'Job';
	books[19] = 'Psalms';
	books[20] = 'Proverbs';
	books[21] = 'Ecclesiastes';
	books[22] = 'Song of Solomon';
	books[23] = 'Isaiah';
	books[24] = 'Jeremiah';
	books[25] = 'Lamentations';
	books[26] = 'Ezekiel';
	books[27] = 'Daniel';
	books[28] = 'Hosea';
	books[29] = 'Joel';
	books[30] = 'Amos';
	books[31] = 'Obadiah';
	books[32] = 'Jonah';
	books[33] = 'Micah';
	books[34] = 'Nahum';
	books[35] = 'Habakkuk';
	books[36] = 'Zephaniah';
	books[37] = 'Haggai';
	books[38] = 'Zechariah';
	books[39] = 'Malachi';
	books[40] = 'Matthew';
	books[41] = 'Mark';
	books[42] = 'Luke';
	books[43] = 'John';
	books[44] = 'Acts';
	books[45] = 'Romans';
	books[46] = '1 Corinthians';
	books[47] = '2 Corinthians';
	books[48] = 'Galatians';
	books[49] = 'Ephesians';
	books[50] = 'Philippians';
	books[51] = 'Colossians';
	books[52] = '1 Thessalonians';
	books[53] = '2 Thessalonians';
	books[54] = '1 Timothy';
	books[55] = '2 Timothy';
	books[56] = 'Titus';
	books[57] = 'Philemon';
	books[58] = 'Hebrews';
	books[59] = 'James';
	books[60] = '1 Peter';
	books[61] = '2 Peter';
	books[62] = '1 John';
	books[63] = '2 John';
	books[64] = '3 John';
	books[65] = 'Jude';
	books[66] = 'Revelation';
	
	return books[bookID];
}
//---------------------------------
//----------DROP DOWN MENU
//---------------------------------
$(document).ready(function() {
	function generateChapterList(chapters){
		$("dl#chapter ul").empty().html("<h3>Chapter</h3>");
		var i=1;
		for (i=1;i<= chapters;i++) {
		
			$("dl#chapter ul").append("<li><a>" + i + "</a></li>");
		}
		$("#book.dropdown dd .wrap").hide();
	    $("#chapter.dropdown dd .wrap").toggle();
	}
	$("#book.dropdown dt a").click(function() {
		$("#chapter.dropdown dd .wrap").hide();
	    $("#book.dropdown dd .wrap").toggle();
	});
	            
	$("#book.dropdown dd .wrap ul li a").click(function() {
	    var text = $(this).html();
	    var chapters = $(this).attr("data-chapters");
	    $("#book.dropdown dt a h2 span.book").html(text);
	    $("#book.dropdown dd .wrap").hide();
	    generateChapterList(chapters);
	});
	
	
	$("#chapter.dropdown dt a").click(function() {
	    $("#book.dropdown dd .wrap").hide();
	    $("#chapter.dropdown dd .wrap").toggle();
	});
	            
	$("#chapter.dropdown dd ul li a").live( "click", function() {
	    var text = $(this).html();
	    var book = $("dl#book h2 .value").text();
	    $("#chapter.dropdown dt a h2 span.chapter").html(text);
	    $("#chapter.dropdown dd .wrap").hide();
	    getChapter(book, text);
	});
	            
	function getSelectedValue(id) {
	    return $("#" + id).find("dt a span.value").html();
	}
	
	$(document).bind('click', function(e) {
	    var $clicked = $(e.target);
	    if (! $clicked.parents().hasClass("dropdown"))
	        $(".dropdown dd .wrap").hide();
	});

	$("#book_code.dropdown dt a").click(function() {
	    $("#book_code.dropdown dd .wrap").toggle();
	});
	
	$("#book_code.dropdown dd .wrap ul li a").click(function() {
	    var text = $(this).html();
	    var book_code = $(this).attr("data-code");
	    $("#book_code.dropdown dt a h2 span.book_name").html(text);
	    $("#book_code.dropdown dd .wrap").hide();
	    loadTOC(book_code);
	});
	
	$("#select_egw.dropdown dt a").click(function() {
	    $("#select_egw.dropdown dd .wrap").toggle();
	});
	
	$("#select_egw.dropdown dd .wrap ul li a").click(function() {
	    var text = $(this).html();
	    var book_code = $(this).attr("data-code");
	    $("#bookshelf .hint.no_results").hide();
	    addBook(book_code, text, "egw");
	    $("#select_egw.dropdown dd .wrap").hide();
	});
	
	$(document).on("click", ".egw p a", function(e) {
	  e.preventDefault();
	});

});
