
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
    xAxis,
    yAxis;

var lineGen = d3.line()
  .x(function(d) {
    return xScale(d.filename.split('_').slice(-1)[0]);
  })
  .y(function(d) {
    return yScale(d.size);
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
        allVersions.push(item.filename);
        allVersionsRange.push(MARGINS.left + rangeCount*rangeWidth);
        rangeCount++;
    });

    xScale.domain(allVersions)
        .range(allVersionsRange),
    yScale.domain([d3.min(data, function(d) {
            return d.size;
        }), d3.max(data, function(d) {
            return d.size;
        })]),
    xAxis = d3.axisBottom().scale(xScale),
    yAxis = d3.axisLeft().scale(yScale);

    console.log('after axis');

	vis.append("svg:g")
		.attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
		.call(xAxis);
	vis.append("svg:g")
		.attr("transform", "translate(" + (MARGINS.left) + ",0)")
	    .call(yAxis);

	console.log('after append');

    vis.append('svg:path')
        .attr('d', lineGen(data))
        .attr('stroke', 'blue')
        .attr('stroke-width', 2)
        .attr('fill', 'none');

    console.log('after append2');

});