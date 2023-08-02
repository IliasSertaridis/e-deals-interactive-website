function onSuccess(responseText) {
    var result = JSON.parse(responseText);
    console.log(result);
    document.getElementById("data").innerHTML = result[1][0];
}
const request = $.ajax({
    url: "http://localhost/api/admins",
    type: "GET",
});
request.done(onSuccess);
request.fail(function() {
    console.log("ERROR");
});
