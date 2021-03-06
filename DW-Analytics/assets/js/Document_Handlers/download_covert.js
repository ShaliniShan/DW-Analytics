var downloadable = (function() {
		/**
		 * Chooses the correct params for JSONtoCSVConvertor
		 * @param  {String} type The Type of button that was clicked
		 */
    function download_info(type, specific) {
        switch (type) {
            case 'Event-All':
                JSONtoCSVConvertor(barGraph.getData(type), "Beacon-Interactions", barGraph.fileName, true);
                break;
			case 'Event-Hourly':
				    var title = specific.concat("Hourly-Beacon-Interactions");
					//JSONtoCSVConvertor(smallBarGraph.getData(type), 'Beacon-Hourly-Interactions', smallBarGraph.fileName, true);
				    JSONtoCSVConvertor(smallBarGraph.getData(type), title, smallBarGraph.fileName, true);
					break;
			case 'Event-Speakers':
					JSONtoCSVConvertor(barGraph.getData(type), 'Speaker-Ratings', barGraph.fileName, true);
					break;
			case 'Event-Sessions':
                    JSONtoCSVConvertor(barGraph.getData(type), "Session-Attendance", barGraph.fileName, true);
                    break;
			case 'Event-Exhibitor-Rating':
					JSONtoCSVConvertor(barGraph.getData(type), 'Exhibitor-Ratings', barGraph.fileName, true);
					break;
			case 'Event-Exhibitor-Interactions':
				    JSONtoCSVConvertor(barGraph.getData(type), 'Exhibitor-Interactions', barGraph.fileName, true);
				    break;
            default:
                console.log("Illegal Type For Download");
                break;
        }
    }
		/**
		 * Converts JSON Object from D3 or Table to CSV
		 * @param {JSON} JSONData      The JSON object that is obtained from the webpage
		 * @param {String} ReportTitle The Additional name appended to the file names
		 * @param {String} FileName    The name of the file
		 * @param {Boolean} ShowLabel  Appends the label when true
		 */
    function JSONtoCSVConvertor(JSONData, ReportTitle, FileName, ShowLabel) {
        var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

        var CSV = '';

        CSV += ReportTitle + '\r\n\n';

        if (ShowLabel) {
            var row = "";

            for (var index in arrData[0]) {
                row += index + ',';
            }

            row = row.slice(0, -1);

            CSV += row + '\r\n';
        }

        for (var i = 0; i < arrData.length; i++) {
            var row = "";

            for (var index in arrData[i]) {
                row += '"' + arrData[i][index] + '",';
            }

            row.slice(0, row.length - 1);

            CSV += row + '\r\n';
        }
        if (CSV == '') {
            alert("Invalid data");
            return;
        }
        var fileName = FileName;
        var reportSubTitle = ReportTitle;
        var match_array = reportSubTitle.match(/Hourly-Beacon-Interaction/i);
        if (match_array) {
        	/*
        	 * It is beacon hourly interactions
        	 */
        	var titleSplit = reportSubTitle.split(":");
        	var beaconName = titleSplit[0];
        	var reportNewTitle = titleSplit[1];
        	fileName += '-' + reportNewTitle + '-' + beaconName;
        } else {
            fileName += '-' + ReportTitle.replace(/ /g, "_");
        }
        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
        var link = document.createElement("a");
        link.href = uri;
        link.style = "visibility:hidden";
        link.download = fileName + ".csv";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    return {
        graph: download_info,
    };
})();
