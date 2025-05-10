let basicSettings = document.getElementById("basicsettings");
let icon = document.getElementById("icon");
let thumbnail = document.getElementById("thumbnail");

function hideEverythingBut(thingtohide) {
    basicSettings.classList.add("hidden");
    icon.classList.add("hidden");
    thumbnail.classList.add("hidden");
    thingtohide.classList.remove("hidden");
}
