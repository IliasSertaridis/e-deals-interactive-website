function onSuccess(responseText) {
    document.getElementById("data").innerHTML = responseText;
}
const request = $.ajax({
    url: "http://localhost/api/admins",
    type: "GET"
});
request.done(onSuccess);
request.fail(function() {
    console.log("ERROR");
});
