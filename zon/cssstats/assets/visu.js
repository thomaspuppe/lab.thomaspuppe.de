
var vis = d3.select("#visu"),
    spark = d3.select("#spark"),
    spark2 = d3.select("#spark2"),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
        top: 20,
        right: 40,
        bottom: 20,
        left: 50
    },
    xScale = d3
    	.scaleOrdinal()
    	.range([MARGINS.left, WIDTH - MARGINS.right]),
    yScale = d3
    	.scaleLinear()
    	.range([HEIGHT - MARGINS.top, MARGINS.bottom]),
    y2Scale = d3
      .scaleLinear()
      .range([HEIGHT - MARGINS.top, MARGINS.bottom]),
    ySparkScale = d3
      .scaleLinear()
      .range([100, 0]),
    ySpark2Scale = d3
      .scaleLinear()
      .range([100, 0]),
    xAxis,
    yAxis,
    y2Axis;

var getNameFromFilename = function(filename) {
  // OPTIMIZE
  return filename.split('_').slice(-1)[0].replace(/.css/i, '').replace(/v/i, '');
}

var globalLineX = function(d) {
  return xScale(getNameFromFilename(d.filename));
}

var lineGen = d3.line()
    .x(globalLineX)
    .y(function(d) {
      return yScale(d.size);
    });

var line2Gen = d3.line()
    .x(globalLineX)
    .y(function(d) {
      return y2Scale(d.selectors.total);
    });


var sparkLineGen = d3.line()
    .x(globalLineX)
    .y(function(d) {
      return ySparkScale(d.selectors.specificity.average);
    });

var spark2LineGen = d3.line()
    .x(globalLineX)
    .y(function(d) {
      return ySpark2Scale(d.declarations.properties['z-index'].length);
    });

d3.json('stats.json', function(error, data) {

    var allVersions = [];
    var allTickValues = [];
    var allVersionsRange = [];
    var rangeCount = 0;
    var rangeWidth = Math.floor((WIDTH - MARGINS.right)/data.length);
    data.forEach(function(item) {

        var versionName = getNameFromFilename(item.filename);
        allTickValues.push(rangeCount%5 === 0 ? versionName : '');
        allVersions.push(versionName);
        allVersionsRange.push(MARGINS.left + rangeCount*rangeWidth);
        rangeCount++;
    });

    xScale.domain(allVersions)
        .range(allVersionsRange),
    yScale.domain([0, d3.max(data, function(d) {
            return d.size;
        })]),
    xAxis = d3.axisBottom().scale(xScale).tickValues(allTickValues),
    yAxis = d3.axisLeft().scale(yScale),
    y2Scale.domain([0, d3.max(data, function(d) {
            return d.selectors.total;
        })]),
    y2Axis = d3.axisRight().scale(y2Scale);

    ySparkScale.domain([d3.min(data, function(d) {
            return d.selectors.specificity.average;
        }), d3.max(data, function(d) {
            return d.selectors.specificity.average;
        })]),

    ySpark2Scale.domain([0, d3.max(data, function(d) {
            return d.declarations.properties['z-index'].length;
        })]),

	vis.append("svg:g")
		.attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
		.call(xAxis);

	vis.append("svg:g")
		.attr("transform", "translate(" + (MARGINS.left) + ",0)")
	    .call(yAxis);

  vis.append("svg:g")
    .attr("transform", "translate(" + (WIDTH - MARGINS.right) + ",0)")
      .call(y2Axis);

    vis.append('svg:path')
        .attr('d', lineGen(data))
        .attr('class', 'path');

    vis.append('svg:path')
        .attr('d', line2Gen(data))
        .attr('class', 'path path--red');

    spark.append('svg:path')
        .attr('d', sparkLineGen(data))
        .attr('class', 'path');


  spark.append('circle')
     .attr('class', 'sparkcircle')
     .attr('cx', xScale(getNameFromFilename(data[rangeCount-1].filename)))
     .attr('cy', ySparkScale(data[rangeCount-1].selectors.specificity.average))
     .attr('r', 2.5); 

  spark2.append('svg:path')
      .attr('d', spark2LineGen(data))
      .attr('class', 'path');

});

/*

D3 wrappers:
- http://metricsgraphicsjs.org/examples.htm#multilines
- http://c3js.org/samples/simple_xy_multiple.html
- 

*/