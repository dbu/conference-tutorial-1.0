/*
 * Oldschool js adapted from
 * http://aktuell.de.selfhtml.org/artikel/javascript/fader-framework/bilderslideshow.htm
 * as i wanted to avoid dropping jquery into this project just for this.
 *
 * Use some javascript library in a real project.
 */

function getSlides() {
    return document
        .getElementById("main-slideshow")
        .getElementsByTagName("div").item(0)
        .getElementsByTagName("ul").item(0)
        .getElementsByTagName("li")
    ;
}

function slideNext() {

    var slides = getSlides();

    if (typeof(counter) != "number") {
        counter = 0;
    }

    slides[counter].style.display = "none";

    counter++;

    if (counter >= slides.length) {
        counter = 0;
    }
    slides[counter].style.display = "block";

    window.setTimeout(slideNext, 3000);
}
window.addEventListener("DOMContentLoaded", slideNext, false);
