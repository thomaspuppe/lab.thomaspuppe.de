var fs = require('fs')
var cssstats = require('cssstats')
 

function getFiles (dir) {
    var files = [];
    var filesInDir = fs.readdirSync(dir);
    for (var i in filesInDir){
        files.push(dir + '/' + filesInDir[i]);
    }
    return files;
}

function getStatsForFile (filename) {
    console.log('read file ' + filename);
    var css = fs.readFileSync(filename, 'utf8'),
        stats =  cssstats(css);
    return stats;
}

function reduceStats (stats) {
    
    delete stats.selectors.values;
    delete stats.rules.size.graph;

    // TODO: umkehren, und nur bestimmte Properties behalten!
    delete stats.declarations.properties.height;
    delete stats.declarations.properties.left;
    delete stats.declarations.properties.overflow;
    delete stats.declarations.properties.position;
    delete stats.declarations.properties.top;
    delete stats.declarations.properties.margin;
    delete stats.declarations.properties.width;
    delete stats.declarations.properties['letter-spacing'];
    delete stats.declarations.properties['font-weight'];

    return stats;
}

function saveObjectToFile(item) {
    var objectString = JSON.stringify(item, null, '\t');

    fs.writeFile("./../stats.json", objectString, function(err) {
        if(err) {
            return console.log(err);
        }
        console.log("The file was saved!");
    }); 
}

function main () {
    var fileList = getFiles('./../files'),
        allStats = [],
        file,
        stats;

    fileList.forEach( function(file) {
        stats = getStatsForFile(file);
        stats = reduceStats(stats);
        stats.filename = file;
        allStats.push(stats);
    });

    saveObjectToFile(allStats);
}

main();





/*
var css = fs.readFileSync('./../files/screen_v2.87.css', 'utf8')

var stats = cssstats(css)
console.log(stats)

var statsString = JSON.stringify(stats)
console.log(statsString)

fs.writeFile("./../stats.json", statsString, function(err) {
    if(err) {
        return console.log(err);
    }
    console.log("The file was saved!");
}); 
*/


// https://github.com/cssstats/cssstats-core
// http://c3js.org/samples/simple_multiple.html
// http://www.d3noob.org/2014/07/d3js-multi-line-graph-with-automatic.html
// https://gist.github.com/mbostock/3884955