var base_image_url = "https://image.tmdb.org/t/p/w300";
var null_image_url = "img/no-poster.jpg";
var result_array = [];

var total_results_num = 0;
var total_pages_num = 0;

var page_no = 1;
var in_trailer = "";

function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
		 	break;
		}
	}
}

function ajax(urlParam, callBackFunction) {
	let httpRequest = new XMLHttpRequest();
	httpRequest.open("GET", urlParam);
	httpRequest.send();
	httpRequest.onreadystatechange = function() {
		if(httpRequest.readyState == 4) {
			// We've gotten a full repsonse back
			if( httpRequest.status == 200 ) {
				// http code 200 means succes
				callBackFunction(httpRequest.responseText);
			}
			else {
				alert("AJAX Error!!!");
			}
		}
	}	
}

function getTrailer(id){
	let httpRequest2 = new XMLHttpRequest();
	httpRequest2.open("GET", "http://api.themoviedb.org/3/movie/" + id + "/videos?api_key=7ce481b3d6492ae0a9dcb56a931a1579", false);
	httpRequest2.send();
	if( httpRequest2.status == 200 ) {
		// http code 200 means succes
		var trailer_response_object = JSON.parse(httpRequest2.responseText);

		if(trailer_response_object.results.length != 0){
			tmp = trailer_response_object.results[0].key;
		}
		else {
			tmp = "";
		}
	}
	else {
		alert("Error for trailer!!!");
	}
	return tmp;
}

function updateResults(text){
	var response_object = JSON.parse(text);
	console.log(response_object);

	result_array = [];
	for (let i = 0; i < response_object.results.length; i++) {
		let in_id = response_object.results[i].id;

		//OBTAIN TRAILER
		in_trailer = getTrailer(in_id);

		let in_title = response_object.results[i].original_title;
		let in_image = response_object.results[i].poster_path;
		let in_backdrop = response_object.results[i].backdrop_path;

		let in_overview = response_object.results[i].overview;
		/*if (in_overview.length > 200) {
			in_overview = in_overview.substring(0, 200) + "...";
		}*/

		let in_release = response_object.results[i].release_date;
		let in_rating = response_object.results[i].vote_average;
		let in_voters = response_object.results[i].vote_count;

		result_array.push({
			id: in_id,
			backdrop_path: in_backdrop,
			trailer_path: in_trailer,
			movie_title: in_title,
			image_url: in_image,
			synopsis: in_overview,
			release_date: in_release,
			rating: in_rating,
			voter_count: in_voters
		});
	}

	total_results_num = response_object.total_results;
	total_pages_num = response_object.total_pages;

	if(total_results_num == 0){
		// document.querySelector("#results-area").innerHTML = "";
		// document.querySelector("#error-term").innerHTML = term;
		// document.querySelector("#num-results-page").innerHTML = "0";
		// document.querySelector("#num-results-total").innerHTML = "0";
		// document.querySelector("#tell-query").innerHTML = term;
	}
	else {
		displayResults();
	}
}

/*
<div id="title-row">
	<div id="showing-results">
		Showing <span id="results-on-page">x</span> result(s) of <span id="total-on-page">y</span> for "<span id="term">1</span>"
	</div>
	<div id="results-pages">
		Page <span id="current-page">1</span> of <span id="total-pages">1</span>
	</div>
</div>
*/

function displayResults () {

	let title_row = document.createElement("div");
	title_row.id = "title-row";

	//RESULTS

	let showing_results = document.createElement("div");
	showing_results.id = "showing-results";
	
	let results_on_page = document.createElement("span");
	results_on_page.id = "results-on-page";
	results_on_page.innerHTML = result_array.length;

	let total_on_page = document.createElement("span");
	total_on_page.id = "total-on-page";
	total_on_page.innerHTML = total_results_num;

	let term_span = document.createElement("span");
	term_span.id = "term";
	term_span.innerHTML = search_term;

	showing_results.innerHTML = "Showing ";
	showing_results.appendChild(results_on_page);
	showing_results.innerHTML += " result(s) of ";
	showing_results.appendChild(total_on_page);
	showing_results.innerHTML += " for \"";
	showing_results.appendChild(term_span);
	showing_results.innerHTML += "\"";

	//PAGES

	let results_pages = document.createElement("div");
	results_pages.id = "results-pages";

	let current_page = document.createElement("span");
	current_page.id = "current-page";
	current_page.innerHTML = page_no;

	let total_pages = document.createElement("span");
	total_pages.id = "total-pages";
	total_pages.innerHTML = total_pages_num;

	results_pages.innerHTML = "Page ";
	results_pages.appendChild(current_page);
	results_pages.innerHTML += " of ";
	results_pages.appendChild(total_pages);

	//APPEND

	title_row.appendChild(showing_results);
	title_row.appendChild(results_pages);

	sleep(1000);
	document.querySelector("#results").innerHTML = "";
	document.querySelector("#results").appendChild(title_row);
	
	/*
	<div class="result-item">
		<div class="rc1">
			<img class="poster-image" src="img/no-poster.jpg" height="190px">
		</div>

		<div class="rc2">
			<span class="movie-title">Title</span>
			<span class="genre">Genre</span> | <span class="rating">Rating</span> | <span>Release Date</span>
			<p class="synopsis">This is a synopsis</p>
		</div>

		<div class="rc3">
			<iframe width="190" height="100" src="https://www.youtube.com/embed/Rxmw9eizOAo" frameborder="0" 
			allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			<button>Add to List</button>
		</div>
	</div>
	*/

	for (let i = 0; i < 20; i++) {
		let result_unit = document.createElement("div");
		result_unit.className = "result-item";
		result_unit.id = String(i);


		//RC1
		let rc1 = document.createElement("div");
		rc1.className = "rc1";

		let poster_image = document.createElement("img");
		poster_image.className = "poster-image";

		if (result_array[i].image_url != null) {
			poster_image.src = base_image_url + result_array[i].image_url;
		}
		else {
			poster_image.src = null_image_url;
		}

		poster_image.alt = result_array[i].movie_title + " poster image";

		rc1.appendChild(poster_image);


		//RC2
		let rc2 = document.createElement("div");
		rc2.className = "rc2";

		let movie_title = document.createElement("span");
		movie_title.className = "movie-title";
		movie_title.innerHTML = result_array[i].movie_title;

		let rating = document.createElement("span");
		rating.className = "rating";
		rating.innerHTML = "Rating: " + result_array[i].rating + " | ";

		let release_date = document.createElement("span");
		release_date.className = "release_date";
		release_date.innerHTML = "Release Date: " + result_array[i].release_date;			

		let synopsis = document.createElement("p");
		synopsis.className = "synopsis";
		synopsis.innerHTML = result_array[i].synopsis;

		rc2.appendChild(movie_title);
		rc2.appendChild(rating);
		rc2.appendChild(release_date);
		rc2.appendChild(synopsis);



		//RC3
		let rc3 = document.createElement("div");
		rc3.className = "rc3";
		rc3.innerHTML = "<iframe width=\"190\" height=\"100\" src=\"https://www.youtube.com/embed/" + result_array[i].trailer_path + "\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";

		let add_button = document.createElement("button");
		add_button.innerHTML = "Add to List";

		var movie_info = "?id=" + result_array[i].id + "&title=" + result_array[i].movie_title + 
			"&watched=no&starred=no&synopsis=" + result_array[i].synopsis + "&poster_path=" + result_array[i].image_url + 
			"&backdrop_path=" + result_array[i].backdrop_path + "&trailer_path=" + result_array[i].trailer_path + 
			"&rating=" + result_array[i].rating + "&release_date=" + result_array[i].release_date + "&list_id=0";

		add_button.value = movie_info;

		add_button.addEventListener("click", function(){
			if(is_logged_in){
				document.querySelector("#overlay").style.display = "block";
				document.querySelector("#add-to-list").style.display = "flex";
				document.querySelector("#add-to-list form").action += add_button.value;
			}
			else{
				document.querySelector("#overlay").style.display = "block";
				document.querySelector("#not-logged-in").style.display = "flex";
			}
		});

		rc3.appendChild(add_button);

		//APPEND ALL
		result_unit.appendChild(rc1);
		result_unit.appendChild(rc2);
		result_unit.appendChild(rc3);

		document.querySelector("#results").appendChild(result_unit);
	}		
}

document.querySelector("#search-form").onsubmit = function(event) {
	event.preventDefault();

	if(document.querySelector("#search-bar").value.length != 0){

		search_term = document.querySelector("#search-bar").value;
		let url = "https://api.themoviedb.org/3/search/movie?api_key=7ce481b3d6492ae0a9dcb56a931a1579&query=" + 
		search_term.split(' ').join('+');
		
		// Call the ajax function
		ajax(url, updateResults);
	}
}

// document.querySelector("#search-query").onclick = function(){
// 	this.setSelectionRange(0, this.value.length);
// };
ajax("https://api.themoviedb.org/3/search/movie?api_key=7ce481b3d6492ae0a9dcb56a931a1579&query=" + search_term.split(' ').join('+'), updateResults);