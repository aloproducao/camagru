var video = document.querySelector('video');
var canvas = document.querySelector('canvas');
var camera = document.getElementById('camera');
var webcamButton = document.getElementById('webcam-button');
var uploadButton = document.getElementById('upload-button');
var webcamDiv = document.getElementsByClassName('webcam')[0];
var welcomeDiv = document.getElementsByClassName('welcome')[0]
var toolDiv = document.getElementsByClassName('tool')[0]
var uploadDiv = document.getElementsByClassName('upload')[0];
var imageFromCanvas = document.getElementById('imageFromCanvas');

var ctx = canvas.getContext('2d');
var vWidth = 640;
var vHeight = 480;

canvas.setAttribute('width', vWidth);
canvas.setAttribute('height', vHeight);


// reveals webcam form
webcamButton.addEventListener('click', function () {
  navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia);

  // Playing a video but hidden from view
  navigator.getUserMedia ({
    video: true
  }, function (localMediaStream) {
    video.src = window.URL.createObjectURL(localMediaStream);
    video.play();
    webcamDiv.style.display = 'block';
    welcomeDiv.style.display = 'none';
    toolDiv.style.display = 'block';

    imageFromCanvas.value = 1;

    // Draws the image to the canvas with clipart superimposed
    window.setInterval(function () {
      var data, pixels;

      ctx.drawImage(video, 0, 0, vWidth, vHeight);
      ctx.drawImage(document.getElementById('preview'), 0, 0);
    }, 100);
  }, function (err) {
    console.log("The following error occured: " + err);
  });
});

// reveals the file upload form when upload button is clicked
uploadButton.addEventListener('click', function () {
  uploadDiv.style.display = 'block';
  welcomeDiv.style.display = 'none';
  toolDiv.style.display = 'block';
});

var clipart = document.getElementById('clipart');
var superposition = document.getElementById('superposition');
var imageFromFile = document.getElementById('imageFromFile');
var preview = document.getElementById('preview');

// taking the picture that you keep when click superposition
superposition.addEventListener('click', function () {
  ctx.drawImage(video, 0, 0, vWidth, vHeight);
  dataURL = canvas.toDataURL('image/png');
  image = dataURL.split('base64,')[1];
  imageFromCanvas.value = image;
});

// checking if clipart is selected AND (webcam picture is present OR file is uploaded)
clipart.addEventListener('change', function (e) {
  var value = e.target.value;

  if (value != '') {
    preview.setAttribute('src', 'photos/' + value);
    if (imageFromFile.value) {
      superposition.style.display = 'block';
    }
    else if (imageFromCanvas.value) {
      superposition.style.display = 'block';
    }
  }
  else {
    superposition.style.display = 'none';
    preview.setAttribute('src', '');
  }
});

// lets you press enter
document.addEventListener('keypress', function (e) {
  if (e.which == '13' && superposition.style.display === 'block') {
    document.getElementById('mixer').submit();
  }
});

// Ajax to delete pictures

var buttons = document.getElementsByClassName('suppress-picture');
for (var i = 0; i < buttons.length; i++) {
  buttons[i].addEventListener('click', function (e) {
    var pictureId = this.getAttribute('data-picture');
    var li = document.getElementById('p' + pictureId);
    var cf = confirm('Are you sure you want to delete this awesome picture?');

    if (cf) {
      var r = new XMLHttpRequest();
      r.open('POST', 'picture_delete.php', true);
      r.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      r.onreadystatechange = function () {
        if (r.readyState != 4 || r.status != 200)
          return;
        console.log(r.responseText);
        li.parentNode.removeChild(li);
      };
      r.send("picture_id=" + pictureId);
    }
  }, true);
}