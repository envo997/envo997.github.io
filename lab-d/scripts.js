const API_KEY = 'f8707f73757335da4673f3e5c8a6fda7';

document.getElementById('search-btn').addEventListener('click', () => {
  const city = document.getElementById('city-input').value;
  if (!city) return alert('Wpisz nazwę miejscowości!');

  getWeatherWithXHR(city);
  getForecastWithFetch(city);
});

function getWeatherWithXHR(city) {
  const xhr = new XMLHttpRequest();
  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}&units=metric&lang=pl`;

  xhr.open('GET', url, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      console.log("Current Weather (XHR):", data);
      displayCurrent(data);
    }
  };
  xhr.send();
}

function getForecastWithFetch(city) {
  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${API_KEY}&units=metric&lang=pl`;

  fetch(url)
    .then(response => response.json())
    .then(data => {
      console.log("Forecast (Fetch):", data);
      displayForecast(data);
    });
}

function displayCurrent(data) {
  const container = document.getElementById('current-weather-results');
  container.style.border = "none";
  container.style.background = "rgba(255, 255, 255, 0.1)";

  container.innerHTML = `
      <div class="weather-card">
          <h2>Aktualnie w: ${data.name}</h2>
          <p><strong>${data.main.temp}°C</strong></p>
          <p>${data.weather[0].description}</p>
      </div>`;
}

function displayForecast(data) {
  const container = document.getElementById('forecast-results');
  container.style.border = "none";
  container.style.background = "rgba(255, 255, 255, 0.1)";

  container.innerHTML = '<h3>Prognoza 5-dniowa:</h3>';
  data.list.slice(0, 5).forEach(item => {
    container.innerHTML += `
        <div style="border-bottom: 1px solid rgba(255,255,255,0.1); padding: 5px 0;">
            ${item.dt_txt.split(' ')[1]} - <strong>${item.main.temp}°C</strong>
        </div>`;
  });
}
