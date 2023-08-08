var supermarket_data;
var convenience_store_data;
var deal_store_data;

var supermarket_query = $.ajax({
    url: "http://localhost/api/stores/supermarket",
    type: "GET",
    fail: function() {console.log("Supermarket Data DB Error");},
    success: function(responseText) {supermarket_data = JSON.parse(responseText);}
});
var convenience_store_query = $.ajax({
    url: "http://localhost/api/stores/convenience",
    type: "GET",
    fail: function() {console.log("Convenience Store Data DB Error");},
    success: function(responseText) {convenience_store_data = JSON.parse(responseText);}
});

var deal_stores_query = $.ajax({
    url: "http://localhost/api/stores/dealStores",
    type: "GET",
    fail: function() {console.log("Deals Store Data DB Error");},
    success: function(responseText) {deal_store_data = JSON.parse(responseText);}
});

$.when(supermarket_query, convenience_store_query, deal_stores_query).then(function() {
    onSuccess()
});

function onSuccess() {
    let mymap = L.map('mapid');
    let osmUrl='https://tile.openstreetmap.org/{z}/{x}/{y}.png';
    let osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    let osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
    mymap.addLayer(osm);
    mymap.setView([38.246242, 21.7350847], 16);
    L.control.locate().addTo(mymap);

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
    var Supermarkets_Layer = new L.GeoJSON(supermarket_data, SupermarketOptions);
    Supermarkets_Layer.addTo(mymap);

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
    var Conveniece_Store_Layer = new L.GeoJSON(convenience_store_data, ConvenieceStoreOptions);
    Conveniece_Store_Layer.addTo(mymap);

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

    var Deal_Stores_Layer = new L.GeoJSON(deal_store_data, DealsOptions);
    Deal_Stores_Layer.addTo(mymap);

    //base layer for layer control
    var baseMaps = {
        "OpenStreetMap": osm
    };

    //extra layers for layer control
    var overlayMaps = {
        "Supermarkets": Supermarkets_Layer,
        "Convenience Stores": Conveniece_Store_Layer,
        "Deal Stores": Deal_Stores_Layer
    };

    //layer control initialization
    L.control.layers(baseMaps,overlayMaps).addTo(mymap);

    //Παλιός κώδικας από διαφάνειες
    /*var featuresLayer = new L.GeoJSON(data, {
    onEachFeature: function (feature, marker) {
      marker.bindPopup("<h4>" + feature.properties.name + "</h4>");
    }
  });
  featuresLayer.addTo(mymap);*/

    let controlSearch = new L.Control.Search({
        position: "topright",
        layer: Supermarkets_Layer, Conveniece_Store_Layer, Deal_Stores_Layer,
        propertyName: "name",
        initial: false,
        zoom: 14,
        marker: false
    });

    mymap.addControl(controlSearch);
}
