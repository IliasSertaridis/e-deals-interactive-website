const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

const form = document.getElementById('uploadForm');
const uploadTrigger = document.getElementById('uploadButton')
if (uploadTrigger)
{
    uploadTrigger.addEventListener('click', () => {
        if(document.getElementById("file").value == "") {
            showAlert('You must first select a file to upload!', 'danger');
        }
        else {
            document.getElementById("uploadForm").submit();
            
            /*const url = new URL(form.action);
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);
            const fetchOptions = {
                method: form.method,
            };
            if (form.method.toLowerCase() === 'post') {
                if (form.enctype === 'multipart/form-data') {
                    fetchOptions.body = formData;
                }
                else {
                    fetchOptions.body = searchParams;
                }
            }
            else {
                url.search=searchParams;
            }

            fetch(url, fetchOptions)
                .then((response) => response.json)
                .then((json) => console.log(json))
                .then(showAlert('Data uploaded successfully!', 'success'));*/
        }
    })
}

const itemsDeleteTrigger = document.getElementById('itemsDeleteButton')
if (itemsDeleteTrigger)
{
    itemsDeleteTrigger.addEventListener('click', () => {
        fetch("items/delete", {
            method: "GET" // default, so we can ignore
        }).then((response) => response.json)
            .then((json) => console.log(json))
            .then(showAlert('Data deleted successfully!', 'success'));
    })
}

const storesDeleteTrigger = document.getElementById('storesDeleteButton')
if (storesDeleteTrigger)
{
    storesDeleteTrigger.addEventListener('click', () => {
        fetch("stores/delete", {
            method: "GET" // default, so we can ignore
        }).then((response) => response.json)
            .then((json) => console.log(json))
            .then(showAlert('Data deleted successfully!', 'success'));
    })
}
