document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if(togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if(type === 'password') {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    }
});

function loginBtn(){
    let user_name = document.getElementById('username');
    let user_password = document.getElementById('password');
    let remember_me = document.getElementById('rememberme');
    let login_button = document.getElementById('loginbutton');
    let formdata = new FormData();
    if (user_name.value == "") {
        alert("Please input your username");
    } else if (user_password.value == "") {
        alert("Please input your password");
    } else {
    formdata.append("usn", user_name.value);
    formdata.append("pw", user_password.value);
    formdata.append("remember-me", remember_me.value);
    formdata.append("lbtn", login_button.value);
    fetch('config.php',{
        method: 'POST',
        body: formdata
    })
    .then(responce => responce.text())
	.then(data => {
		if (data==1) {
			window.location.href = "home.php";
		} else {
			notif(data);
			user_name.value = "";
			user_password.value = "";
            remember_me.value = "";
		}
	})

	return false;

    }
}

function regBtn(){
    let user_name = document.getElementById('username');
    let user_email = document.getElementById('email');
    let user_password = document.getElementById('password');
    let user_confirm_password = document.getElementById('confirm_password');
    let user_usertype = document.getElementById('accounttype');
    let reg_button = document.getElementById('registerbutton');
    let formdata = new FormData();
    if (user_name.value == "") {
        alert("Please input your username");
        return false;
    } else if (user_email.value == "") {
        alert("Please input your email");
        return false;
    } else if (user_password.value == "") {
        alert("Please input your password");
        return false;
    } else if (user_confirm_password.value == "") {
        alert("Please confirm your password");
        return false;
    } else if (user_password.value !== user_confirm_password.value) {
        alert("Passwords do not match");
        return false;
    } else {
        formdata.append("username", user_name.value);
        formdata.append("email", user_email.value);
        formdata.append("password", user_password.value);
        formdata.append("utype", user_usertype.value);
        formdata.append("regBtn", reg_button.value);
        fetch('config.php',{
            method: 'POST',
            body: formdata
        })
        .then(response => response.text())
        .then(data => {
            if(data == "success"){
                alert("Registration successful! Please login.");
                window.location.href = "index.php";
            } else {
                alert(data);
            }
        });
        return false;
    }
}
