function openSignUpOverlay(event) {
    if (event) event.preventDefault();
    closeSignInOverlay();
    document.getElementById("signup_overlay").classList.add("active");
    document.body.classList.add("no_scroll");
}

function closeSignUpOverlay() {
    document.getElementById("signup_overlay").classList.remove("active");
    document.body.classList.remove("no_scroll");
}

function closeSignUpOverlay() {
    document.getElementById("signin_overlay").classList.remove("active");
    document.body.classList.remove("no_scroll");
}