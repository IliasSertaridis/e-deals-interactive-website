$.ajax({
    url: '/api/profile/reviewsHistory',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#reviews-history-table tbody').append("<tr><th scope='row'>" + response[i].review_id  + "</th><td>" + response[i].uploader_username + "</td><td>" + response[i].item_name + "</td><td>" + response[i].rating.charAt(0).toUpperCase() + response[i].rating.slice(1) + "</td></tr>");
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

