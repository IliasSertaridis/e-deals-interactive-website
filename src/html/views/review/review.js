var store_id;
const offersElem = document.getElementById('offers');
const detailsElem = document.getElementById('details');

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

$(function(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    store_id = urlParams.get('store_id');
    //$("#greeter").html("Hello again")
    console.log(store_id);
    displayOffers(store_id);
});

function clearDiv(name) {
    // access the div element and use the replaceChildren() method to clear the div content
    let div = document.getElementById(name);
    div.replaceChildren();
 }

function displayOffers(store_id) {
    var results;
    var id = store_id;
    $.ajax({ 
        url: "/api/map/getOffers",
        type: "GET",
        data: {store_id:store_id},
        async: false, //We only want the whole layer to load asynchronously at once
        fail: function() {console.log("Offer Info DB Error");},
        success: function(data) {
            var response = JSON.parse(data);
            console.log(response);
            results = `<table class="table table-striped"><tr><th>Name</th><th>Price</th><th>Registered</th><th>Expires</th><th>Likes</th><th>Dislikes</th><th>In Stock</th><th>Hot Daily</th><th>Hot Weekly</th><th>View Details</th>`;
            var stock;
            for(element in response){
                if(response[element].in_stock == 1){
                    stock = 'Yes';
                } else {
                    stock = "No";
                }
                response[element].hot_daily = response[element].hot_daily ? 'Yes' : 'No';
                response[element].hot_weekly = response[element].hot_weekly ? 'Yes' : 'No';
                results += `<tr>
                <td>${response[element].name}</td>
                <td>${response[element].price}</td>
                <td>${response[element].registered}</td>
                <td>${response[element].expires}</td>
                <td>${response[element].likes}</td>
                <td>${response[element].dislikes}</td>
                <td>${stock}</td>
                <td>${response[element].hot_daily}</td>
                <td>${response[element].hot_weekly}</td>
                <td><button class="btn btn-primary" id="searchButton" onclick="productDetails('${response[element].name}')">View Details</button></td>
                </tr>`;
            }
            results += `</table>`;
            offersElem.innerHTML = results;
        }
           
    });

}

function imageExists(image_url){

    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;

}

function productDetails(name){
    clearDiv('offers');
    console.log(name);
    $.ajax({ 
        url: "/api/review/offerDetails",
        type: "GET",
        data: {store_id:store_id, name:name},
        async: false, //We only want the whole layer to load asynchronously at once
        fail: function() {console.log("Offer Details DB Error");},
        success: function(data) {
            var response = JSON.parse(data);
            console.log(response);
            results = `<table class="table table-striped"><tr><th>Name</th><th>Price</th><th>Registered</th><th>Expires</th><th>Likes</th><th>Dislikes</th><th>In Stock</th><th>Hot Daily</th><th>Hot Weekly</th><th>Uploader</th><th>Uploader Score</th>`;
            var stock;
            for(element in response){
                if(response[element].in_stock == 1){
                    stock = 'Yes';
                } else {
                    stock = "No";
                }
                response[element].hot_daily = response[element].hot_daily ? 'Yes' : 'No';
                response[element].hot_weekly = response[element].hot_weekly ? 'Yes' : 'No';
                results += `<tr>
                <td>${response[element].name}</td>
                <td>${response[element].price}</td>
                <td>${response[element].registered}</td>
                <td>${response[element].expires}</td>
                <td>${response[element].likes}</td>
                <td>${response[element].dislikes}</td>
                <td>${stock}</td>
                <td>${response[element].hot_daily}</td>
                <td>${response[element].hot_weekly}</td>
                <td>${response[element].username}</td>
                <td>${response[element].total_score}</td>
                </tr>`;
            }
            results += `</table>`;
            let text = response[element].name;
            let icon_name = text.replaceAll(" ","_");
            console.log(icon_name);
            let icon_url = `views/icons/` + icon_name + `.jpg`;
            console.log(icon_url);
            /*$.get(icon_url)
                .done(function(icon_url) { 
                    results += `<img src="${icon_url}" class="img-fluid" alt="Responsive image">`;
                }).fail(function() { 
                    results += `<img src="views/icons/404.jpg" class="img-fluid" alt="Responsive image">`;
            });*/
            if(imageExists(icon_url)){
                results += `<img src="${icon_url}" class="img-fluid" alt="Responsive image">`;
            } else {
                results += `<img src="views/icons/404.jpg" class="img-fluid" alt="Responsive image">`;
            }
            if(stock== 'Yes'){
                results += `<div>
                <label class="form-label">Add Review:</label>
                <select id="review" class="form-select" aria-label="Default select example">
                    <option value="like">Like</option>
                    <option value="dislike">Dislike</option>    
                </select> <br>
                <button class="btn btn-primary" id="searchButton" onclick="addReview('${response[element].offer_id}')">Submit Review</button> 
                </div> <br>`;
            } 
            results += `<div>
            <label class="form-label">Is this product still in stock?</label>
            <select id="in_stock" class="form-select" aria-label="Default select example">
                <option value="yes">Yes</option>
                <option value="no">No</option>    
            </select> <br>
            <button class="btn btn-primary" id="searchButton" onclick="updateStock('${response[element].offer_id}')">Update Stock</button>
            </div>`;
            detailsElem.innerHTML = results;
            console.log(results);
        }
           
    });
}

function removeDetails(){
    clearDiv('details');
    displayOffers(store_id);
}

function addReview(offer_id){
    var selVal = document.querySelector('#review').value;
    console.log(selVal);
    console.log(offer_id);
    $.ajax({
        url: "/api/review/offerReview",
        type: "POST",
        data: {offer_id:offer_id, review:selVal},
        async: false, //We only want the whole layer to load asynchronously at once
        fail: function() {console.log("Offer Details DB Error");},
        success: function(data){
            var response = JSON.parse(data);
            console.log(response);
        }
    });
}

function updateStock(offer_id){
    var selVal = document.querySelector('#in_stock').value;
    console.log(selVal);
    console.log(offer_id);
    $.ajax({
        url: "/api/review/updateStock",
        type: "POST",
        data: {offer_id:offer_id, in_stock:selVal},
        async: false, //We only want the whole layer to load asynchronously at once
        fail: function() {console.log("Update Stock DB Error");},
        success: function(data){
            var response = JSON.parse(data);
            console.log(response);
        }
    });
}