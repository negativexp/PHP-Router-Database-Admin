function mobilenav() {
    const nav = document.querySelector("nav")
    if(!nav.classList.contains("open")) {
        nav.classList.toggle("open")
    } else {
        nav.classList.toggle("open")
    }
}
function displayPopupForm2() {
    const popupForm = document.getElementById("popupForm2")
    popupForm.style.zIndex = 99
}
function hidePopupForm2() {
    const popupForm = document.getElementById("popupForm2")
    popupForm.style.zIndex = -1
}
function displayPopupForm() {
  const popupForm = document.getElementById("popupForm")
  popupForm.style.zIndex = 99
}
function hidePopupForm() {
  const popupForm = document.getElementById("popupForm")
  popupForm.style.zIndex = -1
}

function subnav(subNavId) {
    const subNav = document.getElementById(subNavId);
    subNav.classList.toggle("subnavopen")
}