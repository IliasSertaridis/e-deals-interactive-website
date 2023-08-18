$(function(){
  $("#login-nav").attr("class", "nav-link active");
});

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const showAlert = (message, type) => {
  alertPlaceholder.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')
}

const loginTrigger = document.getElementById('loginButton')
if (loginTrigger)
{
    loginTrigger.addEventListener('click', () => {

        var username = $("#username").val();
        var password = $("#password").val();
        $.ajax({
            url: '/api/login',
            type: "POST",
            data: {username:username, password:password},
            dataType: 'json',
            fail: function() {
                showAlert("Failed to connect to database", 'danger');
            },
            success: function(response) {
                if(response.status == 1) {
                    window.location.href = "/map";
                }
                else if (response.status == 2) {
                    window.location.href = "/admin";
                }
                else {
                    showAlert('Login failed!', 'danger');
                }
            }
        });
    })
}
