"use strict";

class Sound {
    sound;

    constructor(src) {
        this.sound = document.createElement("audio");
        this.sound.src = src;
        this.sound.setAttribute("preload", "auto");
        this.sound.setAttribute("controls", "none");
        this.sound.style.display = "none";
        document.body.appendChild(this.sound);
    }

    play() {
        this.sound.play();
    }
}

const counterElement = document.getElementById("fixed-count");
const notif = new Sound("sounds/Notif.mp3");

async function updateCounter() {
    const oldValue = counterElement.innerText;
    const response = await fetch(`fixed-count.php?value=${ oldValue }`);
    const newValue = await response.text();
    console.log(oldValue, newValue, oldValue !== newValue);
    if (oldValue !== newValue) {
        counterElement.innerText = newValue;
        if (oldValue !== "...") {
            notif.play();
            counterElement.classList.remove("counter-update");
            void counterElement.offsetWidth; // trigger reflow https://stackoverflow.com/a/58353279
            counterElement.classList.add("counter-update");
        }
    }

    updateCounter();
}

updateCounter();
