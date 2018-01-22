var tableFill = (function () {
	"use strict";
	/**
	  * @private 
	  * @param data 	Array from PHP Database of KEY => VALUE pairs converted to JSON
	  * @param title    Object that Specifies the Axis Titles and the specific type the x axis is (Either svg:text or svg:image)
	  */
	function loadBarGraph(data, title) {
		barGraph.loadData(data, title);
		barGraph.plotBarGraph();
	}
	/**
	  * @private 
	  * @param data 	Array from PHP Database of KEY => VALUE pairs converted to JSON
	  * @param title    Object that Specifies the Axis Titles and the specific type the x axis is (Either svg:text or svg:image)
	  */
	function loadSmBarGraph(data, title) {
		smallBarGraph.loadData(data, title);
		smallBarGraph.plotBarGraph();
	}
	/**
	  * @public 
	  */
	function selectClassForFill(select, data) {
		var tableSelected = $('.table > tbody > tr');	 
		switch (select) {
			case 'organizer':
				loadBarGraph(data, ({x : "Beacons", y : "Number Of Interactions", z : 'img'}));
				break;
			case 'organizer_hourly': 
				loadSmBarGraph(data, ({x : "Beacons", y : "Number Of Interactions", z : 'text'}));
				break;
			case 'organizer_all': 
				loadBarGraph(data, {x : "Beacons", y : "Number Of Interactions", z : 'text'});
				break;
			case 'organizer_session':
				loadBarGraph(data, {x : "Sessions", y : "Number Of Interactions", z : 'text'});
				break;
			case 'organizer_speakers':
				loadBarGraph(data, {x : "Speakers", y : "Ratings", z : 'img'});
				break;
			case 'exhibitor':
				loadBarGraph(data, {x : "Beacons", y : "Number Of Interactions", z : 'text'});
				break;
			case 'exhibitor_hourly':
				loadSmBarGraph(data, {x : "Times", y : "Number Of Interactions", z : 'text'});
				break;
			default:
				console.log("Error : Could not fill Data");
				break;
		}
	}

	/**
	  * @return Object This 
	  */
	return {
		selectFill : selectClassForFill
	};
})(); 