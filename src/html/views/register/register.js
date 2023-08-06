const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

const validateEmail = (email) => {
  return String(email)
    .toLowerCase()
    .match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};

const validatePassword = (password) => {
    var regularExpression = /^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
    if(password.length > 8 && password.match(regularExpression)) {
        return true;
    }
    else {
        return false;
    }
}

const registerTrigger = document.getElementById('registerButton')
if (registerTrigger)
{
    registerTrigger.addEventListener('click', () => {
        var username = $("#username").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var repeat_password = $("#repeat_password").val();
        if(username && email && password && repeat_password) {
            if(!validateEmail(email)) {
                showAlert("Email does not have the correct format!", "danger");
            }
            else if (!validatePassword(password)) {
                showAlert("Password must contain at least 8 characters, one number, one special character and one upper case letter!", "danger");
            }
            else if(password !== repeat_password) {
                showAlert("Passwords do not match!", "danger");
            }
            else {
                $.ajax({
                url: '/register',
                type: "POST",
                data: {username:username, email:email, password:password},
                dataType: 'json',
                fail: function() {
                    showAlert("Failed to connect to database", 'danger');
                },
                success: function(response) {
                    if(response.status == 1) {
                        showAlert('Registration completed successfully!', 'success');
                    }
                    else {
                        showAlert('Registration failed!', 'danger');
                    }
                }
                });
            }
        }
        else {
            showAlert("You must fill all fields!", "danger");
        }
    })
}
