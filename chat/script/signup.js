function signup_validate(){
    const nameField = document.getElementById('name');
    const numberField = document.getElementById('number');
    const passwordField = document.getElementById('password');
    const emailField = document.getElementById('email');
    const imageField = document.getElementById('inputGroupFile02');

    if (nameField.value.trim() === '') {
        alert('Please enter your Name.');
        return false;
    }

    if (numberField.value.trim() === '') {
        alert('Please enter your Mobile Number.');
        return false;
    }

    if (emailField.value.trim() === '') {
        alert('Please enter your Email.');
        return false;
    }

    if (passwordField.value.trim() === '') {
        alert('Please enter your Password.');
        return false;
    }

    if (imageField.files.length === 0) {
        alert('Please select a Profile Picture.');
        return false;
    }
    return true;
}

function login_validate()
{
    const numberField = document.getElementById('number');
    const passwordField = document.getElementById('password');

    if (numberField.value.trim() === '') {
        alert('Please enter your Mobile Number.');
        return false;
    }
    if (passwordField.value.trim() === '') {
        alert('Please enter your Password.');
        return false;
    }
}

function update_validate()
{
    
}

const pswrdField = document.querySelector(".form input[type='password']"),
toggleIcon = document.querySelector(".form .field i");

toggleIcon.onclick = () =>{
    if(pswrdField.type === "password"){
        pswrdField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    }
    else{
        pswrdField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}