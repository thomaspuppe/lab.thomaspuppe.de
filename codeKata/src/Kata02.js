function chopA(search, arr) {

    if (typeof arr !== 'object') {
        return -1;
    }

    return arr.indexOf(search);

}

function chopB(search, arr) {

    if (typeof arr !== 'object') {
        return -1;
    }

    if (arr.length === 0) {
        return -1;
    }

    var i,
        arrLength = arr.length;

    for (i=0;i<arrLength;i++) {
        if (arr[i] === search) {
            return i;
        }
    }

    return -1;

}