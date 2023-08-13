$(function(){
  $("#map-nav").attr("class", "nav-link active");
    dealStoresQuery('');
    initializeMap();
    categoriesQuery = $.ajax({
        url: '/api/admin/statistics/categories',
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
});

var mymap;
var layerControl;

var Supermarkets_Layer;
var Conveniece_Store_Layer;
var Deal_Stores_Layer;


function supermarketQuery(text, type) {
    var data = [];
    if (type === 'name') {
        data = {name:text};
    }
    else if (type === 'category') {
        data = {category:text};
    }
    var supermarket_query = $.ajax({
        url: "http://localhost/api/stores/supermarket",
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
        url: "http://localhost/api/stores/convenience",
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
        url: "http://localhost/api/stores/dealStores",
        type: "GET",
        data: data,
        fail: function() {console.log("Deals Store Data DB Error");},
        success: function(responseText) {
            var deal_store_data = JSON.parse(responseText);
            console.log(deal_store_data);
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
        return L.marker(latlng, {icon: supermarket_icon}).bindPopup("<h4>" + feature.properties.name + "</h4>")
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
        return L.marker(latlng, {icon: convenience_store_icon}).bindPopup("<h4>" + feature.properties.name + "</h4>")
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

function addDealStoresData(deal_store_data) {
    //replace leaflet's blue marker with custom convenience-store icon for convenience-stores
    function Deals_Icon(feature,latlng){
        let deals_icon = L.icon({
            iconUrl: 'views/map/hot-deal.png',
            iconSize:     [32, 32], // size of the icon
            iconAnchor:   [16, 32], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
        })
        return L.marker(latlng, {icon: deals_icon}).bindPopup("<h4>" + feature.properties.name + "</h4>")
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
    console.log(category);
    dealStoresQuery(category, 'category');
}

function removeFilter() {
    mymap.off();
    mymap.remove();
    initializeMap('');
    dealStoresQuery('');
}
