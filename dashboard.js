async function getWeatherData(latitude = 54.9733, longitude = -1.614) {
  try {
    const API_URL = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,rain,snowfall,cloud_cover,visibility,wind_speed_10m,uv_index&timezone=Europe%2FLondon`;
    const response = await fetch(API_URL);
    return await response.json();
  } catch (error) {
    console.error("Error fetching weather data:", error);
    return null;
  }
}

async function getCoordinates(location) {
  try {
    const geocodingAPI = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(location)}&count=1&language=en&format=json`;
    const response = await fetch(geocodingAPI);
    const data = await response.json();
    if (data.results && data.results.length > 0) {
      return {
        latitude: data.results[0].latitude,
        longitude: data.results[0].longitude
      };
    } else {
      throw new Error('Location not found');
    }
  } catch (error) {
    console.error('Error getting coordinates:', error);
    return null;
  }
}

function populateData(day, data) {
  ["morning", "afternoon", "evening"].forEach((time) => {
    document.getElementById(`${day}-${time}-temp`).textContent = data[time].temp ?? "-";
    document.getElementById(`${day}-${time}-humidity`).textContent = data[time].humidity ?? "-";
    document.getElementById(`${day}-${time}-wind`).textContent = data[time].wind ?? "-";
    document.getElementById(`${day}-${time}-uv`).textContent = data[time].uv ?? "-";
    document.getElementById(`${day}-${time}-visibility`).textContent = data[time].visibility ?? "-";
  });
}

function setupTabSwitching() {
  const tabs = document.querySelectorAll(".tabs ul li");
  const tabContents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      const selectedTab = this.dataset.tab;


      tabs.forEach((t) => t.classList.remove("is-active"));
      tabContents.forEach((content) => content.classList.remove("is-active"));


      this.classList.add("is-active");
      document.getElementById(selectedTab).classList.add("is-active");
    });
  });
}

async function initDashboard() {
  let weatherData;
  const coordinates = await getCoordinates(locationName);

  if (coordinates) {
    weatherData = await getWeatherData(coordinates.latitude, coordinates.longitude);
  } else {
    weatherData = await getWeatherData();
  }

  if (weatherData) {
    document.querySelector(".subtitle").textContent = `Weather information for ${locationName}`;

    const days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    days.forEach((day, index) => {
      const startIndex = index * 24;
      populateData(day, {
        morning: {
          temp: weatherData.hourly.temperature_2m[startIndex + 8],
          humidity: weatherData.hourly.relative_humidity_2m[startIndex + 8],
          wind: weatherData.hourly.wind_speed_10m[startIndex + 8],
          uv: weatherData.hourly.uv_index[startIndex + 8],
          visibility: weatherData.hourly.visibility[startIndex + 8],
        },
        afternoon: {
          temp: weatherData.hourly.temperature_2m[startIndex + 14],
          humidity: weatherData.hourly.relative_humidity_2m[startIndex + 14],
          wind: weatherData.hourly.wind_speed_10m[startIndex + 14],
          uv: weatherData.hourly.uv_index[startIndex + 14],
          visibility: weatherData.hourly.visibility[startIndex + 14],
        },
        evening: {
          temp: weatherData.hourly.temperature_2m[startIndex + 20],
          humidity: weatherData.hourly.relative_humidity_2m[startIndex + 20],
          wind: weatherData.hourly.wind_speed_10m[startIndex + 20],
          uv: weatherData.hourly.uv_index[startIndex + 20],
          visibility: weatherData.hourly.visibility[startIndex + 20],
        },
      });
    });
  }

  setupTabSwitching();
}

document.addEventListener("DOMContentLoaded", initDashboard);
