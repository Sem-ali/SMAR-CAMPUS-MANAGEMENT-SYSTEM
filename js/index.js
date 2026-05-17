function validateForm() {
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if (name === "" || email === "" || password === "") {
        alert("All fields are required!");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters!");
        return false;
    }
    return true;
}
function validateRequestForm() {

    let title = document.getElementById("title").value.trim();
    let description = document.getElementById("description").value.trim();

    if (title === "" || description === "") {
        alert("Please fill all fields.");
        return false;
    }

    return true;
}
