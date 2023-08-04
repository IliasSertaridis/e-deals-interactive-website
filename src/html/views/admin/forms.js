const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

const uploadTrigger = document.getElementById('uploadButton')
if (uploadTrigger)
{
    uploadTrigger.addEventListener('click', () => {
        //document.getElementById("uploadForm").submit();

        var fd = new FormData();
        var files = $('#file')[0].files;
        if (files.length > 0) {
            fd.append('file',files[0]);
            $.ajax({
                url: window.location.href + '/upload',
                type: "POST",
                data: fd,
                dataType: 'json',
                contentType: false,
                processData: false,
                fail: function() {
                    showAlert("Failed to connect to database", 'danger');
                },
                success: function(response) {
                    if(response.status == 1) {
                        showAlert('Item data uploaded successfully!', 'success');
                    }
                    else if(response.status == 2) {
                        showAlert('Price data uploaded successfully!', 'success');
                    }
                    else if(response.status == 3) {
                        showAlert('Store data uploaded successfully!', 'success');
                    }
                    else {
                        showAlert('Data upload failed!', 'danger');
                    }
                }
            });
        }
        else {
            showAlert('You must first select a file to upload!', 'danger');
        }
    })
}

const deleteTrigger = document.getElementById('deleteButton')
if (deleteTrigger)
{
    deleteTrigger.addEventListener('click', () => {
        $.ajax({
            url: window.location.href + '/delete',
            type: "GET",
            fail: function() {
                showAlert("Failed to connect to database", 'danger');
            },
            success: function(responseText) {
                var response = String(responseText);
                if(response.trim() === 'SUCCESS') {
                    showAlert('Data deleted successfully!', 'success');
                }
                else {
                    showAlert('Data deletion failed!', 'danger');
                }
            }
        });
    })
}
