$.ajax({
    url: '/api/profile/dealsHistory',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#deals-history-table tbody').append("<tr><th scope='row'>" + response[i].offer_id  + "</th><td>" + response[i].name + "</td><td>" + response[i].price + "</td><td>" + response[i].store_name +"</td><td>" + response[i].number_of_likes + "</td><td>" + response[i].number_of_dislikes + "</td><td>" + response[i].registration_date + "</td><td>" + response[i].expiration_date + "</tr>");
        }
        if (response.length === 0) {
            $('#no-deals').removeAttr('hidden');
        }
        else {
            $('#no-deals').attr('hidden','true');
            $('#deals-history-table').removeAttr('hidden')
        }
    }
});

