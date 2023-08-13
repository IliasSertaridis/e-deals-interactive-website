$.ajax({
    url: '/api/profile/scoreHistory',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#score-history-table tbody').append("<tr><th scope='col'>" + response[i].current_score  + "</th><td>" + response[i].total_score + "</td><td>" + response[i].last_month_tokens + "</td><td>" +  response[i].total_tokens + "</td></tr>");
        }
        $('#score-history-table').removeAttr('hidden')
    }
});

