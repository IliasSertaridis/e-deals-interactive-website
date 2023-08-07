const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

const validatePassword = (password) => {
    var regularExpression = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
    if(password.length > 8 && password.match(regularExpression)) {
        return true;
    }
    else {
        return false;
    }
}

function applyChanges(new_username, new_password, repeat_password) {
    var valid  = true;
    if(new_password && repeat_password) {
        if (!validatePassword(new_password)) {
            showAlert("Password must contain at least 8 characters, one number, one special character and one upper case letter!", "danger");
            valid = false;
        }
        else if(new_password !== repeat_password) {
            showAlert("Passwords do not match!", "danger");
            valid = false;
        }
    }
    if(valid) {
        $.ajax({
            url: '/api/profile/details/change',
            type: "POST",
            data: {username:new_username, password:new_password},
            dataType: 'json',
            fail: function() {
                showAlert("Failed to connect to database", 'danger');
            },
            success: function(response) {
                if(response.status == 1) {
                    showAlert('Details updated successfully!', 'success');
                }
                else {
                    showAlert('Update failed!', 'danger');
                }
            }
        });
    }
}

const applyTrigger = document.getElementById('applyButton')
if (applyTrigger)
{
    applyTrigger.addEventListener('click', () => {
        var new_username = $("#new_username").val();
        var new_password= $("#new_password").val();
        var repeat_password = $("#repeat_password").val();
        var old_password = $("#old_password").val();
        if((new_username !== '' || new_password !== '' || repeat_password !== '') && old_password !== '') {
            $.ajax({
                url: '/api/profile/details/verify',
                type: "POST",
                data: {password:old_password},
                dataType: 'json',
                fail: function() {
                    showAlert("Failed to connect to database", 'danger');
                },
                success: function(response) {
                    if(response.status === 1) {
                        applyChanges(new_username, new_password, repeat_password)
                    }
                    else {
                        showAlert("Old Password didn't match!", 'danger');
                    }
                }
            });
        }
        else {
            showAlert("You must enter your old password and least one more field!", "danger");
        }
    })
}
