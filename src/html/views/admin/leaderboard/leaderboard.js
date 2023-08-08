// Basic example
$(document).ready(function () {
    $.ajax({
        url: '/api/admin/leaderboard',
        type: "GET",
        dataType: 'json',
        fail: function() {
            showAlert("Failed to connect to database", 'danger');
        },
        success: function(response) {
            console.log(response);
            for (var i = 0; i < response.length; i++) {
                $('#leaderboard-table tbody').append("<tr><td>" + response[i].position  + "</th><td>" + response[i].username + "</td><td>" + response[i].total_score + "</td><td>" +  response[i].current_score + "</td><td>" +  response[i].total_tokens + "</td><td>" +  response[i].last_month_tokens + "</td></tr>");
            }
            $('#leaderboard-table').DataTable({
                "paging": true, // false to disable pagination (or any other option)
                "searching": true,
                "sorting": false
            });
        }
    });
});
