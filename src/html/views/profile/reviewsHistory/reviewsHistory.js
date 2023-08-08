$.ajax({
    url: '/api/profile/reviewsHistory',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        console.log(response);
        for (var i = 0; i < response.length; i++) {
            $('#reviews-history-table tbody').append("<tr><th scope='row'>" + response[i].review_id  + "</th><td>" + response[i].uploader_username + "</td><td>" + response[i].item_name + "</td><td>" + response[i].rating +"</td><td>" + response[i].number_of_likes + "</td><td>" + response[i].number_of_dislikes + "</td><td>" + response[i].registration_date + "</td><td>" + response[i].expiration_date + "</tr>");
        }
        if (response.length === 0) {
            $('#no-reviews').removeAttr('hidden');
        }
        else {
            $('#no-reviews').attr('hidden','true');
            $('#reviews-history-table').removeAttr('hidden')
        }
    }
});

