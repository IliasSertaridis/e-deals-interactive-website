var mymap;
var layerControl;

var Supermarkets_Layer;
var Conveniece_Store_Layer;
var Deal_Stores_Layer;

var userlat;
var userlng

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
    $("#map-nav").attr("class", "nav-link active");
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userlat = position.coords.latitude;
                userlng = position.coords.longitude;
                dealStoresQuery('');
                initializeMap();
                categoriesQuery = $.ajax({
                    url: '/api/categories',
                    type: "GET",
                    dataType: 'json',
                    fail: function() {
                        showAlert("Failed to connect to database", 'danger');
                    },
                    success: function(response) {
                        for (var i = 0; i < response.length; i++) {
                            $('#categories-dropdown').append("<button class='dropdown-item' onclick='filterCategory(this.innerHTML)'>" + response[i].name  + "</button>");
                        }
                        $('#categories-div').removeAttr('hidden');
                    }
                });
            },
            (error) => {
                showAlert('You need to enable location services for the site to work!', 'danger');
            }
        )
    }
    else {
        showAlert('Necessary location services are not supported by your browser!', 'danger');
    }
});

function supermarketQuery(text, type) {
    var data = [];
    if (type === 'name') {
        data = {name:text};
    }
    else if (type === 'category') {
        data = {category:text};
    }
    var supermarket_query = $.ajax({
        url: "/api/map/stores/supermarket",
        type: "GET",
        data: data,
        fail: function() {console.log("Supermarket Data DB Error");},
        success: function(responseText) {
            var supermarket_data = JSON.parse(responseText);
            if(supermarket_data.features.length !== 0) {
                addSupermarketData(supermarket_data)
                return supermarket_query
            }
        }
    });
}

function convenienceStoreQuery(text, type) {
    var data = [];
    if (type === 'name') {
        data = {name:text};
    }
    else if (type === 'category') {
        data = {category:text};
    }
    var convenience_store_query = $.ajax({
        url: "/api/map/stores/convenience",
        type: "GET",
        data: data,
        fail: function() {console.log("Convenience Store Data DB Error");},
        success: function(responseText) {
            var convenience_store_data = JSON.parse(responseText);
            if(convenience_store_data.features.length !== 0) {
                addConvenienceStoreData(convenience_store_data)
                return convenience_store_query;
            }
        }
    });
}

function dealStoresQuery(text, type) {
    var data = [];
    if (type === 'name') {
        data = {name:text};
    }
    else if (type === 'category') {
        data = {category:text};
    }
    var deal_stores_query = $.ajax({
        url: "/api/map/stores/dealStores",
        type: "GET",
        data: data,
        fail: function() {console.log("Deals Store Data DB Error");},
        success: function(responseText) {
            var deal_store_data = JSON.parse(responseText);
            if(deal_store_data.features.length !== 0) {
                addDealStoresData(deal_store_data);
                return deal_stores_query;
            }
        }
    });
}

function initializeMap() {
    mymap = L.map('mapid');
    let osmUrl='https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    let osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    let osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
    mymap.addLayer(osm);
    mymap.setView([38.246242, 21.7350847], 16);
    L.control.locate().addTo(mymap);

    //base layer for layer control
    var baseMaps = {
        "OpenStreetMap": osm
    };
    //layer control initialization
    layerControl = new L.control.layers(baseMaps).addTo(mymap);
}

function addSupermarketData(supermarket_data) {
    //replace leaflet's blue marker with custom supermarket icon for supermarkets
    function Supermarket_Icon(feature,latlng){
        let supermarket_icon = L.icon({
            iconUrl: 'views/map/supermarket.png',
            iconSize:     [32, 32], // size of the icon
            iconAnchor:   [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
        })
        if(calculateDistance(feature.geometry.coordinates[1], feature.geometry.coordinates[0]) < 50.0) {
        //if(true) {
            return L.marker(latlng, {icon: supermarket_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4><div class=\"text-center\"><button class=\"btn btn-primary\" id=\"filterButton\" text-center onclick=\"submit(" + feature.properties.store_id + ",'" + feature.properties.name + "')\">Submit New Offer</button></div>", {
                maxWidth:1000
            })
        }
        else {
            return L.marker(latlng, {icon: supermarket_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4><div class='text-center'>Get close to add offer</div>", {
                maxWidth:1000
            })
        }
    }

    //create an options object which specifies which function is called on each feature
    let SupermarketOptions = {
        pointToLayer: Supermarket_Icon
    }

    //creating the supermarket layer and adding it to the map
    Supermarkets_Layer = new L.GeoJSON(supermarket_data, SupermarketOptions);
    Supermarkets_Layer.addTo(mymap);
    layerControl.addOverlay(Supermarkets_Layer, 'Supermarkets');
}

function addConvenienceStoreData(convenience_store_data) {
    //replace leaflet's blue marker with custom convenience-store icon for convenience-stores
    function Convenience_Store_Icon(feature,latlng){
        let convenience_store_icon = L.icon({
            iconUrl: 'views/map/convenience-store.png',
            iconSize:     [32, 32], // size of the icon
            iconAnchor:   [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
        })
        if(calculateDistance(feature.geometry.coordinates[1], feature.geometry.coordinates[0]) < 50.0) {
        //if(true){
            return L.marker(latlng, {icon: convenience_store_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4><div class=\"text-center\"><button class=\"btn btn-primary\" id=\"filterButton\" text-center onclick=\"submit(" + feature.properties.store_id + ",'" + feature.properties.name + "')\">Submit New Offer</button></div>", {
                maxWidth:1000
            })
        }
        else {
            return L.marker(latlng, {icon: convenience_store_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4><div class='text-center'>Get close to add offer</div>", {
                maxWidth:1000
            })
        }
    }

    //create an options object which specifies which function is called on each feature
    let ConvenieceStoreOptions = {
        pointToLayer: Convenience_Store_Icon
    }

    //creating the convenience-store layer and adding it to the map
    Conveniece_Store_Layer = new L.GeoJSON(convenience_store_data, ConvenieceStoreOptions);
    Conveniece_Store_Layer.addTo(mymap);
    layerControl.addOverlay(Conveniece_Store_Layer, 'Convenience Stores');
}

function loadStoreOffers(store_id, addReview) {
    var results;
    $.ajax({ 
        url: "/api/map/getOffers",
        type: "GET",
        data: {store_id:store_id},
        async: false, //We only want the whole layer to load asynchronously at once
        fail: function() {console.log("Offer Info DB Error");},
        success: function(data) {
            var response = JSON.parse(data);
            if(addReview) {
                results = `<table class="table table-striped"><tr><th>Name</th><th>Price</th><th>Registered</th><th>Expires</th><th>Likes</th><th>Dislikes</th><th>In Stock</th><th>Hot Daily</th><th>Hot Weekly</th><th>Review</th>`;
                for(element in response){
                    response[element].in_stock = response[element].in_stock ? 'Yes' : 'No';
                    response[element].hot_daily = response[element].hot_daily ? 'Yes' : 'No';
                    response[element].hot_weekly = response[element].hot_weekly ? 'Yes' : 'No';
                    results += `<tr>
                    <td>${response[element].name}</td>
                    <td>${response[element].price}</td>
                    <td>${response[element].registered}</td>
                    <td>${response[element].expires}</td>
                    <td>${response[element].likes}</td>
                    <td>${response[element].dislikes}</td>
                    <td>${response[element].in_stock}</td>
                    <td>${response[element].hot_daily}</td>
                    <td>${response[element].hot_weekly}</td>
                    <td><button type="button" class="btn btn-primary">Review</button></td>
</tr>`;
                }
                results += `</table>`;
            }
            else {
                results = `<table class="table table-striped"><tr><th>Name</th><th>Price</th><th>Registered</th><th>Expires</th><th>Likes</th><th>Dislikes</th><th>In Stock</th><th>Hot Daily</th><th>Hot Weekly</th>`;
                for(element in response){
                    response[element].in_stock = response[element].in_stock ? 'Yes' : 'No';
                    response[element].hot_daily = response[element].hot_daily ? 'Yes' : 'No';
                    response[element].hot_weekly = response[element].hot_weekly ? 'Yes' : 'No';
                    results += `<tr>
                    <td>${response[element].name}</td>
                    <td>${response[element].price}</td>
                    <td>${response[element].registered}</td>
                    <td>${response[element].expires}</td>
                    <td>${response[element].likes}</td>
                    <td>${response[element].dislikes}</td>
                    <td>${response[element].in_stock}</td>
                    <td>${response[element].hot_daily}</td>
                    <td>${response[element].hot_weekly}</td>
</tr>`;
                }
                results += `</table>`;
            }
        }
    });
    return results;

}

function addDealStoresData(deal_store_data) {
    //replace leaflet's blue marker with custom convenience-store icon for convenience-stores
    function Deals_Icon(feature,latlng){
        let deals_icon = L.icon({
            iconUrl: 'views/map/hot-deal.png',
            iconSize:     [32, 32], // size of the icon
            iconAnchor:   [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
        })
        if(calculateDistance(feature.geometry.coordinates[1], feature.geometry.coordinates[0]) < 50.0) {
        //if(true) {
            return L.marker(latlng, {icon: deals_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4>" + loadStoreOffers(feature.properties.store_id,true) + "<div class=\"text-center\"><button class=\"btn btn-primary\" id=\"filterButton\" text-center onclick=\"submit(" + feature.properties.store_id + ",'" + feature.properties.name + "')\">Submit New Offer</button></div>", {
                maxWidth:1000
            });
        }
        else {
            return L.marker(latlng, {icon: deals_icon}).bindPopup("<h4 class=\"text-center h4\">" + feature.properties.name + "</h4>" + loadStoreOffers(feature.properties.store_id,false) + "<div class=\"text-center\">Get close to add and review offers</div>", {
                maxWidth:1000
            });
        }
    }

    //create an options object which specifies which function is called on each feature
    let DealsOptions = {
        pointToLayer: Deals_Icon
    }

    Deal_Stores_Layer = new L.GeoJSON(deal_store_data, DealsOptions);
    Deal_Stores_Layer.addTo(mymap);
    layerControl.addOverlay(Deal_Stores_Layer, 'Stores Currently With Deals');
}

function nameFilter() {
    mymap.off();
    mymap.remove();
    initializeMap();
    supermarketQuery($('#searchForm').val(), 'name');
    convenienceStoreQuery($('#searchForm').val(), 'name');
    dealStoresQuery($('#searchForm').val(), 'name');
}

function filterCategory(category) {
    mymap.off();
    mymap.remove();
    initializeMap();
    dealStoresQuery(category, 'category');
}

function removeFilter() {
    mymap.off();
    mymap.remove();
    initializeMap('');
    dealStoresQuery('');
}

function submit(store_id, name){
    window.location.href = "/submit?store_id=" + store_id + "&name=" + name;

}

function calculateDistance(storelat,storelng) {
  var R = 6371000; // Radius of the earth in m
  var dLat = deg2rad(storelat-userlat);  // deg2rad below
  var dLon = deg2rad(storelng-userlng); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(userlat)) * Math.cos(deg2rad(storelat)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in m
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}
