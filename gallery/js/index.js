//This variable sets how long we wait between images
var minutes = 1;

//This variable is the location of our JSON gallery
var galleryUrl = "https://cdn.rawgit.com/dconnolly/chromecast-backgrounds/master/backgrounds.json";
var imageList;

function cycleImage() {
  var imageNumber = Math.floor((Math.random() * imageList.length) + 1);
  showImage(imageList[imageNumber]['url']);
  setTimeout (cycleImage,minutes*60*1000);
}

function showImage(imageUrl) {
  document.getElementById("image").src=imageUrl;
}

 $.getJSON(galleryUrl,   function(result) { 
  imageList = result;
  cycleImage();
});