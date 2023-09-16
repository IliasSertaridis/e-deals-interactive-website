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
    $("#statistics-nav").attr("class", "nav-link active");
});

var offersChart;
var discountChart;
var discountLabels = [];
var discountData = [];

$(document).ready(function(){
    const date = new Date();
    $('#monthInput').val(String(date.getFullYear()) + '-' + String(date.getMonth()).padStart(2,"0"));
});
offersQuery = $.ajax({
    url: '/api/admin/statistics/offers',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        var offersLabels = [];
        var offersData = [];
        for(day of response) {
            offersLabels.push(day.registration_date);
            offersData.push(day.count);
        }
        const ctx = $('#offersChart');
        offersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: offersLabels,
                datasets: [{
                    label: 'Daily Number of Offers',
                    data: offersData,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspctRatio: false,
                responsive: true,
                scales: {
                    x: {
                        min: `${offersLabels[0].substring(0,7)}-01`,
                        max: `${offersLabels[0].substring(0,7)}-${lastDayOfMonth(offersLabels[0].substring(0,4),offersLabels[0].substring(5,7))}`,
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

categoriesQuery = $.ajax({
    url: '/api/categories',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        for (var i = 0; i < response.length; i++) {
            $('#categories-dropdown').append("<button class='dropdown-item' onclick='selectCategory(this.innerHTML)'>" + response[i].name  + "</button>");
        }
        $('#categories-div').removeAttr('hidden');
    }
});

originalDiscountQuery = $.ajax({
    url: '/api/admin/statistics/discount',
    type: "GET",
    dataType: 'json',
    fail: function() {
        showAlert("Failed to connect to database", 'danger');
    },
    success: function(response) {
        discountLabels = [];
        discountData = [];
        for(day of response) {
            discountLabels.push(day.date);
            discountData.push(day.mean_discount);
        }
        const [week, year] = getWeekAndYearOfDateString(discountLabels[0]);
        const firstDateOfWeek = getFirstDateOfWeek(week, year);
        const lastDateOfWeek = getLastDateOfWeek(week, year);
        const ctx = $('#discountChart');
        discountChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: discountLabels,
                datasets: [{
                    label: 'Mean Discount For (Sub)Category',
                    data: discountData,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspctRatio: false,
                responsive: true,
                scales: {
                    x: {
                        min: firstDateOfWeek,
                        max: lastDateOfWeek,
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

function selectCategory(category) {
    $.ajax({
        url: '/api/subcategories',
        type: "GET",
        data: {category:category},
        dataType: 'json',
        fail: function() {
            showAlert("Failed to connect to database", 'danger');
        },
        success: function(response) {
            for (var i = 0; i < response.length; i++) {
                $('#subcategories-dropdown').append("<button class='dropdown-item' onclick='selectSubcategory(this.innerHTML)'>" + response[i].name  + "</button>");
            }
        }
    });
    $.ajax({
        url: '/api/admin/statistics/discount',
        type: "GET",
        data: {category:category},
        dataType: 'json',
        fail: function() {
            showAlert("Failed to connect to database", 'danger');
        },
        success: function(response) {
            if(response.length !== 0) {
                $('#categoriesButton').html(category);
                $('#subcategories-div').removeAttr('hidden');
                $('#defaultCategoryText').attr('hidden','true');
                discountLabels = [];
                discountData = [];
                for(day of response) {
                    discountLabels.push(day.date);
                    discountData.push(day.mean_discount);
                }
                const [week, year] = getWeekAndYearOfDateString(discountLabels[0]);
                discountChart.config.options.scales.x.min = getFirstDateOfWeek(week, year);
                discountChart.config.options.scales.x.max = getLastDateOfWeek(week, year);
                discountChart.config.data.labels = discountLabels;
                discountChart.config.data.datasets.data = discountData;
                discountChart.update();
            }
            else {
                $('#subcategories-div').attr('hidden', 'true');
                showAlert("No discounts found for category", "info");
            }
        }
    });
}

function selectSubcategory(subcategory) {
    $.ajax({
        url: '/api/admin/statistics/discount',
        type: "GET",
        data: {subcategory:subcategory},
        dataType: 'json',
        fail: function() {
            showAlert("Failed to connect to database", 'danger');
        },
        success: function(response) {
            if (response.length !== 0) {
                $('#subcategoriesButton').html(subcategory);
                discountLabels = [];
                discountData = [];
                for(day of response) {
                    discountLabels.push(day.date);
                    discountData.push(day.mean_discount);
                }
                const [week, year] = getWeekAndYearOfDateString(discountLabels[0]);
                discountChart.config.options.scales.x.min = getFirstDateOfWeek(week, year);
                discountChart.config.options.scales.x.max = getLastDateOfWeek(week, year);
                discountChart.config.data.labels = discountLabels;
                discountChart.config.data.datasets.data = discountData;
                discountChart.update();
            }
            else {
                showAlert("No discounts found for subcategory", "info");
            }
        }
    });
}

const lastDayOfMonth = (year, month) => {
    return new Date(year,month,0).getDate();
}

const getDateString = (date) => {
    var mm = date.getMonth() + 1; // getMonth() is zero-based
    var dd = date.getDate() + 1;

    return [date.getFullYear(),'-',
        (mm>9 ? '' : '0') + mm,'-',
        (dd>9 ? '' : '0') + dd
    ].join('');
};

const getWeekAndYearOfDateString = (dateString) => {
    const date = new Date(dateString.substring(0,4), dateString.substring(5,7)-1, dateString.substring(8,10));
    const year = date.getFullYear();
    const onejan = new Date(date.getFullYear(), 0, 1);
    const week = Math.ceil((((date.getTime() - onejan.getTime()) / 86400000) + onejan.getDay() + 1) / 7);
    return [week, year];
}

const getFirstDateOfWeek = (week, year) => {
    var day = (1 + (week - 1) * 7); // 1st of January + 7 days for each week
    date = new Date(year, 0, day);

    return getDateString(date);
}

const getLastDateOfWeek = (week, year) => {
    var day = (1 + (week - 1) * 7) + 6; // 1st of January + 7 days for each week
    date = new Date(year, 0, day);

    return getDateString(date);
}

function filterMonthChart(selectionMonth) {
    const year = selectionMonth.value.substring(0,4);
    const month = selectionMonth.value.substring(5,7);


    const startDate = `${selectionMonth.value}-01`;
    const endDate = `${selectionMonth.value}-${lastDayOfMonth(year, month)}`;

    offersChart.config.options.scales.x.min = startDate;
    offersChart.config.options.scales.x.max = endDate;
    offersChart.update();
}

function filterWeekChart(week) {
    const startDate = getFirstDateOfWeek(week.value.substring(6,8), week.value.substring(0,4));
    const endDate = getLastDateOfWeek(week.value.substring(6,8), week.value.substring(0,4));

    discountChart.config.options.scales.x.min = startDate;
    discountChart.config.options.scales.x.max = endDate;
    discountChart.update();
}
