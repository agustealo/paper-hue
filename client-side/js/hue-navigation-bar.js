window.onscroll = function() {myFunction()};
var header = document.querySelector(".hue-nav-wraper");
var content = document.querySelector(".article-padding-top");
var position = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > position) {
    header.classList.add("is-fixed");
    content.classList.add("content-padding-top");
  } else {
    header.classList.remove("is-fixed");
    content.classList.remove("content-padding-top");
  }
}
