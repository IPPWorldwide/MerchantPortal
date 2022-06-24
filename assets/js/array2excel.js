function array2excel() {
    var convertedIntoArray = [];
    $("table#tnx_list tr").each(function() {
        var rowDataArray = [];
        var actualData = $(this).find('td');
        if (actualData.length > 0) {
            actualData.each(function() {
                rowDataArray.push($(this).text());
            });
            convertedIntoArray.push(rowDataArray);
        }
    });
    var json = JSON.stringify(convertedIntoArray);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                var file = new Blob([this.response], {type:  "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"});
                var fileURL = URL.createObjectURL(file);
                window.open(fileURL);
            } else {
                alert("Error: " + this.status + "  " + this.statusText);
            }
        }
    }
    request.open('POST', "/export/");
    request.responseType = "blob";
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send("array2excel=" + encodeURIComponent(json));
}