function mobilenav() {
    const nav = document.querySelector("nav")
    if(!nav.classList.contains("open")) {
        nav.classList.toggle("open")
    } else {
        nav.classList.toggle("open")
    }
}
//css
//popupForm
//popupForm2
function MessageBox(id) {
    const message = document.getElementById(id)
    message.classList.toggle("z-top")
    closeContextMenu()
    hideTextOptions()
    hideSecondTextOptions()
}

function subnav(subNavId, acko) {
    const subNav = document.getElementById(subNavId);
    subNav.classList.toggle("subnavopen")
    if(subNav.classList.contains("subnavopen")) {
        acko.style.textDecoration = "underline"
    } else acko.style.textDecoration = "none"
}