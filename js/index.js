var base_image_url = "https://image.tmdb.org/t/p/w300";
var null_image_url = "img/no-poster.jpg";
var result_array = [];

var term = "Now Showing";

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
		var tmp = JSON.parse(httpRequest2.responseText).results[0].key;
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
		var in_trailer = getTrailer(in_id);

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

	total_results = response_object.total_results;

	if(total_results == 0){
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

function displayResults () {
	document.querySelector("#results").innerHTML = "<h2 id=\"title\">Now Showing</h2>";
	/*
	<div class="now-showing-item">
		<div class="rc1">
			<img class="poster-image" src="img/no-poster.jpg" height="190px">
		</div>

		<div class="rc2">
			<span class="movie-title">Title</span>
			<span class="genre">Genre</span> | <span class="rating">Rating</span> | <span>Release Date</span>
			<p class="synopsis">This is a synopsis </p>
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
		result_unit.className = "now-showing-item";
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

	if(document.querySelector("#search-bar").value.length == 0){

		//document.querySelector("#search-bar").className = "invalid-input";
		//document.querySelector("#error-message").style.display = "block";
	}
	else {
		this.submit();
	}
}

ajax("https://api.themoviedb.org/3/movie/now_playing?api_key=7ce481b3d6492ae0a9dcb56a931a1579&language=en-US&page=1", updateResults);