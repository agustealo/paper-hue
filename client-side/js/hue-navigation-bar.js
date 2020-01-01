window.onscroll = function() {myFunction()};
var narBar = document.querySelector(".hue-nav-wraper");
var content = document.querySelector(".site-content");
var position = narBar.offsetTop;

function myFunction() {
  if (window.pageYOffset > position) {
    narBar.classList.add("is-fixed");
    content.classList.add("site-content-padding-top");
  } else {
    narBar.classList.remove("is-fixed");
    content.classList.remove("site-content-padding-top");
  }
}
