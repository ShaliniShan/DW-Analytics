var barGraph = (function () {
	"use strict";
	// local variable for data being queried from parse backend
	var data = [],
		axisLabels = {};
	// Variables
	var w = 700, // width of entire svg element
		h = 470, // height of entire svg element
		margin = { // customs margins for bargraph
			top: 70, 
			bottom: 120,
			left: 50,
			right: 50
		},
		groupSpacing = 10,
		axisFormat = d3.format('.0f'),
		tickNumber = 5,
		width = w - margin.left - margin.right, // width for svg with custom margins
		height = h - margin.top - margin.bottom, // height for svg with custom margins
			/* Flipping the axis */
		x = d3.scale.ordinal() // x axis set up
			.domain(data.map(function(entry) {
				return entry.key;
			}))
			.rangeBands([0, width - groupSpacing]),
		y = d3.scale.linear() // y axis set up
			.domain([0, d3.max(data, function (d) {
				return d.value;
			})])
			.range([height, 0]),
		//Linear sets colors from one range to the end
		linearColorScale = d3.scale.linear()
							.domain([0, data.length])
							.range(["#ececec", "#F68026"]),
			//ordinal branches selection to categories of colors
		/* D3 has default color scales 20 = 20 colors */	
		ordinalColorScale = d3.scale.category20(),
		//setting up x axis label
		xAxis = d3.svg.axis()
				.scale(x)
				.orient("bottom"),
		// setting up y axis label
		yAxis = d3.svg.axis()
				.scale(y)
				.tickFormat(axisFormat)
				.ticks(tickNumber)
				.orient("left"),
		//setting up internal grid lines
		yGridLines = d3.svg.axis()
					.scale(y)
					.tickSize(-width+groupSpacing, 0, 0)
					.tickFormat('')
					.ticks(tickNumber)	
					.orient('left');
	// d3 objects
	var MC = $('#main-container');
	var svg = d3.select("#main-container .row").append("svg") //selects #main-container .row and adds svg to it.
				.attr("id", "chart") // gives svg id of chart
				.attr('width', w) // makes svg width equal to w var
				.classed('col-md-12', true)
				.attr("clear", "both")
				.attr("height", h); // makes svg height equal to h var
	var chart = svg.append("g") // appends group to svg
				.classed('display', true) // gives ground class of display; true is needed to append the class
				.attr("transform", "translate(" + margin.left + "," + margin.top + ")"); // group is adjusted inside svg element
	// this function is --PUBLIC and takes in two arrays from the database - the information and axis labels
	function loadData(arr, names) {
		data = arr;
		axisLabels = names;
	}
	// Initialize the barGraph --PUBLIC function
	function init() {
		plot.call(chart, { // We want the this keyword is bound to the chart object
			data : data,
			axis : {
				x : xAxis,
				y : yAxis
			},
			gridlines : yGridLines,
			init : true
		});
	}

	function axisOptions(options, params) {
		if(options.z == 'img') {
			// Draw the x axis
			this.append("g") //adds group to chart
				.classed("x axis", true) // adds class x and class axis to group
				.attr("transform", "translate(" + 0 + "," + height + ")") //
				.call(params.axis.x)
					.selectAll(".tick").each(function(d,i) {        
				        d3.select(this)
				          .append('image')
				          .attr('xlink:href', data[i].key)
				          .attr('x', -50)
				          .attr('y', 15)
				          .attr('width',100)
				          .attr('height',100);
				    });
		} else {
			// Draw the x axis
			this.append("g") //adds group to chart
				.classed("x axis", true) // adds class x and class axis to group
				.attr("transform", "translate(" + 0 + "," + height + ")") //
				.call(params.axis.x)
					.selectAll(options.z)
						.classed("x-axis-label", true)
						//.style('text-anchor', 'end')
						.attr("dx", -9)
						.attr("dy", 15);
						//.attr('transform', 'translate(0,0) rotate(-45)');
		}
		this.selectAll('.tick text').attr('display', 'none');	
	}

	// Need to draw axis separate from the plot function to avoid it drawing over each other
	function drawAxis(params) {
		if (params.init) {
	//-------------- Draw the gridlines and axes ------------------
			// Draw the gridlines
			this.append('g') // this refers to chart var; adds group
				.call(params.gridlines) // resets yGridlines var
				.classed('gridline', true) // gives group class of gridline
				.attr("transform", "translate(0,0)"); //transform is set to 0,0
			// Add correct attributes of x axis
			axisOptions.call(this, axisLabels, params);
			// Draw the y axis
			this.append("g")
				.classed("y axis", true)
				.attr("transform", "translate(" + 0 + "," + 0 + ")")
				.call(params.axis.y);
			// Draw y label
			this.select('.y.axis')
				.append("text")
				.attr("x", 0)
				.attr("y", 0)
				.style("text-anchor", "middle")
				.attr("transform", "translate(-50," + height/2 + ") rotate(-90)");
				//.text(axisLabels.y);
			/*// Draw x label 
			this.select(".x.axis")
				.append("text")
				.attr("x", 0)
				.attr("y", 0)
				.style("text-anchor", "middle")
				.attr("transform", "translate(" + width/2 + ",80)");*/
				//.text(axisLabels.x);
		} else if (!params.init) {
		//-----------------Update info--------------------
			this.selectAll("g.x.axis")
				.transition()
				.duration(500)
				.ease("bounce")
				.delay(500)
				.call(params.axis.x);
			this.selectAll(".x-axis-label")
				.style('text-anchor', 'end')
				.attr("dx", -9)
				.attr("dy", 8)
				.attr('transform', 'translate(0,0) rotate(-45)');
			this.selectAll("g.y.axis")
				.transition()
				.duration(500)
				.ease("bounce")
				.delay(500)
				.call(params.axis.y);
		}
	}
	// Function to display bar graph
	function plot(params) {
		// re-establish domains of both x and y on each call
		x.domain(data.map(function(entry) {
				return entry.key;
			}));
		y.domain([0, d3.max(data, function (d) {
				return d.value;
			})]);
		//Draw the axes and axes labels
		drawAxis.call(this, params);
		//enter() -- Data is bound to elements
		this.selectAll(".bar")
			.data(params.data)
			.enter()
				.append("rect")
				.classed("bar", true)
				.attr('data-name', function (d, i) {
					var regex = /(\()/;
					var check = regex.exec(d.key);
					var returned_name = check ? d.key.substring(0, check.index) : d.key;
					return returned_name;
				})
				.attr("height",20)
				.on("mouseover", function (d, i) {
					d3.select(this).style("fill", "#00BCD4");
				})
				.on("click", function (d, i) {
					var main_container_url = MC[0].getAttribute('data-href');
					var clicked_url = $(this).attr("data-name");
					if(main_container_url) {
						document.location = main_container_url + clicked_url;
					} else if(MC[0].getAttribute("data-table")) {
						document.location = MC[0].getAttribute("data-table");
					}
				})
				.on("mouseout", function (d, i) {
					d3.select(this).style("fill", linearColorScale(i));
				})
				.append("svg:title")
					.text(function (d) {
						return d.key;
					});
		this.selectAll(".bar-label")
			.data(params.data)
			.enter()
				.append('text')
				.classed("bar-label", true);
		//update -- elements that are bound are updated with changes in data
		this.selectAll(".bar")
			.transition()
			.duration(500)
			.ease("bounce")
			.delay(500)
			.attr("x", function(d, i) {
				return x(d.key);
			})
			.attr("y", function (d, i) {
				return y(d.value);
			})
			.attr("width", function (d, i) {
				return x.rangeBand() - groupSpacing;
			})
			.attr("height", function(d, i) {
				return height - y(d.value);
			})
			.style("fill", function (d,i) {
				return linearColorScale(i);
			});
		this.selectAll(".bar-label")
			.transition()
			.duration(500)
			.ease("bounce")
			.delay(500)
			.attr("x", function (d, i) {
				return x(d.key) + (x.rangeBand()/2 - groupSpacing/2);
			})
			.attr("dx", 0)
			.attr("y", function (d, i) {
				return y(d.value);
			})
			.attr("dy", -6)
			.text(function (d) {
				return d.value;
			});
		//exit() -- any unbound elements are removed
		this.selectAll(".bar")
			.data(params.data)
			.exit()
			.remove();
		this.selectAll(".bar-label")
			.data(params.data)
			.exit()
			.remove();
	}
	// Module API
	var publicAPI = {
		plotBarGraph : init,
		loadData : loadData
	};
	return publicAPI;
})();