let myMap = L.map('mapa').setView([53.430127, 14.564802], 18);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors',
  crossOrigin: true
}).addTo(myMap);

let board = document.getElementById('puzzle-board');
let box = document.getElementById('puzle');
let mainCanvas = document.getElementById('offscreen-canvas');
let ctx = mainCanvas.getContext('2d');

box.addEventListener("dragover", function(e) {
  e.preventDefault();
});

box.addEventListener("drop", function(e) {
  dropPart(e);
});

document.getElementById("getLocation").addEventListener("click", function() {
  if (!navigator.geolocation) {
    console.log("No geolocation.");
  }

  navigator.geolocation.getCurrentPosition(position => {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;

    myMap.setView([lat, lon], 18);
    let marker = L.marker([lat, lon]).addTo(myMap);
    marker.bindPopup("tutaj").openPopup();
  }, err => {
    console.error(err);
  });
});

if (Notification.permission !== 'granted') {
  Notification.requestPermission();
}

document.getElementById("saveMap").addEventListener("click", function() {
  console.log("zapisuje mape");
  leafletImage(myMap, function (err, img) {
    if (err) {
      alert("error mapy");
      return;
    }
    ctx.clearRect(0, 0, 600, 600);
    ctx.drawImage(img, 0, 0, 600, 600);
    makePuzzle();
  });
});

function makePuzzle() {
  box.innerHTML = '';
  board.innerHTML = '';
  let parts = [];

  for (let i = 0; i < 16; i++) {
    let slot = document.createElement('div');
    slot.className = 'tile-slot';
    slot.id = 's-' + i;
    slot.addEventListener("dragover", e => e.preventDefault());
    slot.addEventListener("drop", e => dropPart(e));
    board.appendChild(slot);
  }

  for (let y = 0; y < 4; y++) {
    for (let x = 0; x < 4; x++) {
      let pCanvas = document.createElement('canvas');
      pCanvas.width = 150;
      pCanvas.height = 150;
      let pCtx = pCanvas.getContext('2d');

      pCtx.drawImage(mainCanvas, x * 150, y * 150, 150, 150, 0, 0, 150, 150);

      let pImg = new Image();
      pImg.src = pCanvas.toDataURL();
      pImg.className = 'puzzle-piece';
      pImg.draggable = true;
      pImg.id = 'p-' + y + '-' + x;
      pImg.alt = (y * 4 + x);

      pImg.addEventListener("dragstart", e => {
        e.dataTransfer.setData('id', e.target.id);
      });

      parts.push(pImg);
    }
  }

  parts.sort(() => Math.random() - 0.5);

  parts.forEach(p => {
    box.appendChild(p);
  });
}

function dropPart(e) {
  e.preventDefault();
  let id = e.dataTransfer.getData('id');
  let item = document.getElementById(id);

  if (e.target.className === 'tile-slot' && e.target.children.length === 0) {
    e.target.appendChild(item);
  } else if (e.target.id === 'puzle') {
    e.target.appendChild(item);
  }
  checkGame();
}

function checkGame() {
  let allSlots = document.getElementsByClassName('tile-slot');
  let score = 0;
  let placed = 0;

  for (let i = 0; i < allSlots.length; i++) {
    let slot = allSlots[i];
    let piece = slot.firstChild;
    if (piece) {
      placed++;
      let sNum = slot.id.replace('s-', '');
      if (piece.alt == sNum) score++;
    }
  }
  console.log("wynik: " + score + "/16");

  if (placed === 16) {
    if (score === 16) {
      let alertBox = document.getElementById("custom-alert");
      alertBox.style.display = "block";
      setTimeout(() => { alertBox.style.display = "none"; }, 5000);
      alert("super kot!");
    } else {
      alert("cos zrobiles zle, wynik: " + score + "/16");
    }
  }
}
