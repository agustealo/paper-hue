window.onscroll = function() {myFunction()};
var narBar = document.querySelector(".hue-nav-wraper");
//var content = document.querySelector(".article-padding-top");
var position = narBar.offsetTop;

function myFunction() {
  if (window.pageYOffset > position) {
    narBar.classList.add("is-fixed");
    //content.classList.add("content-padding-top");
  } else {
    narBar.classList.remove("is-fixed");
    //content.classList.remove("content-padding-top");
  }
}
