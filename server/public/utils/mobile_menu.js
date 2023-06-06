// Open and Close Burger Menu
const navMenu = document.querySelector("#nav-menu");
const backDrop = document.querySelector("#backdrop");

const navItems = [navMenu, backDrop]

const onOpen = () => {
    navItems.forEach(item => {
        item.classList.remove("hidden");
    })
    backDrop.addEventListener("click", onClose)
}

const onClose = () => {
    navItems.forEach(item => {
        item.classList.add("hidden");
    })
    backDrop.removeEventListener("click", onClose);
}