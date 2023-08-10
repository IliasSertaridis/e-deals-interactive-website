$(function(){
    $("#statistics-nav").attr("class", "nav-link active");
});

var myChart;

$(document).ready(function(){
    resize();
    $(window).on("resize", function(){                      
        resize();
    });
    const date = new Date();
    $('#monthInput').val(String(date.getFullYear()) + '-' + String(date.getMonth()).padStart(2,"0"));
});
$.ajax({
    url: '/api/admin/statistics',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        console.log(response);
        var labels = [];
        var data = [];
        for(day of response) {
            labels.push(day.registration_date);
            data.push(day.count);
        }
        console.log(labels);
        console.log(data);
        console.log(response);
        const ctx = $('#myChart');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Number of Offers',
                    data: data,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspctRatio: false,
                responsive: true,
                scales: {
                    x: {
                        min: labels[0],
                        max: labels[labels.length - 1],
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});


function filterChart(date) {
    const year = date.value.substring(0,4);
    const month = date.value.substring(5,7);

    const lastDay = (year, month) => {
        return new Date(year,month,0).getDate();
    }

    const startDate = `${date.value}-01`;
    const endDate = `${date.value}-${lastDay(year, month)}`;

    myChart.config.options.scales.x.min = startDate;
    myChart.config.options.scales.x.max = endDate;
    myChart.update();
}
