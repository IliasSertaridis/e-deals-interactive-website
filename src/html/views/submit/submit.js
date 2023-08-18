var store_id;
var last;
var selected_item_id = -1;

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
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    store_id = urlParams.get('store_id');
    store_name = urlParams.get('name');
    $("#greeter").html("Please submit the deal you found for " + store_name + ":")
    initializeItemsList('');
});

function select(element, item_id) {
    if (typeof last !== "undefined") {
        last.setAttribute("class", "accordion-button collapsed");
    }
    if (element.getAttribute("class") === "accordion-button collapsed") {
        element.setAttribute("class", "accordion-button");
    }
    else {
        element.setAttribute("class", "accordion-button collapsed");
    }
    last = element;
    selected_item_id = item_id;
    $('#price-div').removeAttr('hidden');
    $('#submit').removeAttr('hidden');
}

function submit() {
    price =  $('#price').val();
    var isNumber = value => /^\d+(\.\d+)?$/.test(value);
    if(selected_item_id === -1) {
        showAlert("You haven't selected an item!", 'danger');
    }
    else if(!isNumber(price)) {
        showAlert("You haven't entered a valid price!", 'danger');
    }
    else {
        submitQuery = $.ajax({
            url: '/api/submit/submit',
            type: "POST",
            data: {store_id: store_id, item_id: selected_item_id, price: price},
            dataType: 'json',
            fail: function() {
                showAlert("Failed to connect to database", 'danger');
            },
            success: function(response) {
                if(response['status'] === 1) {
                    showAlert("Successfully submitted deal!", 'success');
                }
                else {
                    showAlert("Failed to submit deal!", 'danger');
                }
            }
        });
    }
}

function initializeItemsList(filter) {
    itemsQuery = $.ajax({
        url: '/api/submit/items',
        type: "GET",
        data: {name: filter},
        dataType: 'json',
        fail: function() {
            showAlert("Failed to connect to database", 'danger');
        },
        success: function(response) {
            const categories = getCategories(response);
            const subcategories = getSubcategories(response);
            if(response.length === 0) {
                showAlert('No items found for your filter', 'danger');
            }
            else {
                $('#itemsAccordion').html("");
                for (const category of categories) {
                    $("#itemsAccordion").append(addCategoryItem(category, subcategories, response));
                }
            }
        }
    });
}

function filterItems() {
    filter = $('#filterForm').val();
    $('#price-div').attr('hidden','true');
    $('#submit').attr('hidden','true');
    initializeItemsList(filter);
}

function removeFilter() {
    $('#filterForm').val("");
    $('#price-div').attr('hidden','true');
    $('#submit').attr('hidden','true');
    initializeItemsList('');
}

function getCategories(json) {
    var categories = new Set();
    for (item in json) {
        categories.add(json[item]['category'])
    }
    return categories;
}

function getSubcategories(json) {
    var subcategories = new Map();
    for (item in json) {
        subcategories.set(json[item]['subcategory'], json[item]['category'])
    }
    return subcategories;
}

function getCategorySubcategories(category, subcategories) {
    result = [];
    for (let [key, value] of subcategories) {
        if(value === category) {
            result.push(key);
        }
    }
    return result;
}

function getSubcategoryItems(subcategory, items) {
    result = [];
    for (let item of items) {
        if(item['subcategory'] === subcategory) {
            result.push([item['item_id'], item['name']]);
        }
    }
    return result;
}

function addCategoryItem(category, subcategories, items) {
    const categorySubcategories = getCategorySubcategories(category, subcategories);
    var response = "<div class=\"accordion-item\"><h2 class=\"accordion-header\" id=\"heading" + category.replace(/\s/g, '' ) + "\"><button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse" + category.replace(/\s/g, '' ) + "\" aria-expanded=\"true\" aria-controls=\"collapseOne\">" + category + "</button></h2><div id=\"collapse" + category.replace(/\s/g, '' ) + "\" class=\"accordion-collapse collapse\" aria-labelledby=\"heading" + category.replace(/\s/g, '' ) + "\" data-bs-parent=\"#itemsAccordion\"><div class=\"accordion-body\"><div class=\"accordion\" id=\"sub" + category.replace(/\s/g, '' ) + "-itemsAccordion\">";
    for (subcategory in categorySubcategories) {
        response += addSubcategory(categorySubcategories[subcategory], category, items)
    }
    response += "</div></div></div></div>";
    return response;
}

function addSubcategory(subcategory, category, items) {
    const subcategoryItems = getSubcategoryItems(subcategory, items);
    var response = "<div class=\"accordion-item\"><h2 class=\"accordion-header\" id=\"sub" + category.replace(/\s/g, '' ) + "-heading" + subcategory.replace(/\s/g, '' ) + "\"><button class=\"accordion-button\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#sub" + category.replace(/\s/g, '' ) + "-collapse" + subcategory.replace(/\s/g, '' ) + "\" aria-expanded=\"true\" aria-controls=\"collapse" + subcategory.replace(/\s/g, '' ) + "\">" + subcategory + "</button></h2><div id=\"sub" + category.replace(/\s/g, '' ) + "-collapse" + subcategory.replace(/\s/g, '' ) + "\" class=\"accordion-collapse collapse\" aria-labelledby=\"sub" + category.replace(/\s/g, '' ) + "-heading" + subcategory.replace(/\s/g, '' ) + "\" data-bs-parent=\"#sub" + category.replace(/\s/g, '' ) + "-itemsAccordion\"><div class=\"accordion-body\">"
    for (item in subcategoryItems) {
        response += addItem(subcategoryItems[item]);
    }
    response += "</div></div></div>";
    return response;
}

function addItem(item) {
    return "<button class=\"accordion-button collapsed\" type=\"button\" onclick=\"select(this, " + item[0] + ")\">" + item[1] + "</button>"

}
