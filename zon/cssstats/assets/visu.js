
var vis = d3.select("#visu"),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
        top: 20,
        right: 20,
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
    xAxis,
    yAxis,
    y2Axis;

var getNameFromFilename = function(filename) {
  // OPTIMIZE
  return filename.split('_').slice(-1)[0].replace(/.css/i, '').replace(/v/i, '');
}

var lineGen = d3.line()
  .x(function(d) {
    return xScale(getNameFromFilename(d.filename));
  })
  .y(function(d) {
    return yScale(d.size);
  });

var line2Gen = d3.line()
  .x(function(d) {
    return xScale(getNameFromFilename(d.filename));
  })
  .y(function(d) {
    return y2Scale(d.selectors.total);
  });

/*
vis.append('svg:path')
  .attr('d', lineGen(data))
  .attr('stroke', 'green')
  .attr('stroke-width', 2)
  .attr('fill', 'none');

vis.append('svg:path')
  .attr('d', lineGen(data2))
  .attr('stroke', 'blue')
  .attr('stroke-width', 2)
  .attr('fill', 'none');
*/


d3.json('stats.json', function(error, data) {
	console.log(data);

    var allVersions = [];
    var allVersionsRange = [];
    var rangeCount = 0;
    var rangeWidth = Math.floor((WIDTH - MARGINS.right)/data.length);
    data.forEach(function(item) {
        allVersions.push(getNameFromFilename(item.filename));
        allVersionsRange.push(MARGINS.left + rangeCount*rangeWidth);
        rangeCount++;
    });

    xScale.domain(allVersions)
        .range(allVersionsRange),
    yScale.domain([0, d3.max(data, function(d) {
            return d.size;
        })]),
    xAxis = d3.axisBottom().scale(xScale),
    yAxis = d3.axisLeft().scale(yScale),
    y2Scale.domain([0, d3.max(data, function(d) {
            return d.selectors.total;
        })]),
    y2Axis = d3.axisRight().scale(y2Scale);


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
        .attr('class', 'path')
        .attr('fill', 'none');

    vis.append('svg:path')
        .attr('d', line2Gen(data))
        .attr('class', 'path')
        .attr('fill', 'none');


});