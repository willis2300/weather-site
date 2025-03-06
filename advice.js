console.log("Advice.js has loaded!");

async function getWeatherData(latitude, longitude) {
    try {
        const API_URL = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=temperature_2m,relative_humidity_2m,precipitation,uv_index,wind_speed_10m,visibility&timezone=Europe%2FLondon`;
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

function generateAdvice(temp, humidity, uv, visibility, wind) {
    console.log("Generating advice with data:", { temp, humidity, uv, visibility, wind });

    let advice = [];

    // Temperature advice
    if (temp < 5) {
        advice.push("Wear warm clothing, it's quite cold outside.");
    } else if (temp > 25) {
        advice.push("Stay hydrated and wear light clothing, it's hot outside.");
    }

    // Humidity advice
    if (humidity > 80) {
        advice.push("It’s quite humid, dress accordingly.");
    } else if (humidity < 30) {
        advice.push("The air is dry, consider using moisturizer.");
    }

    // UV index advice
    if (uv > 5) {
        advice.push("Wear sunscreen and sunglasses to protect against strong UV rays.");
    }

    // Visibility advice
    if (visibility < 1000) {
        advice.push("Visibility is low, drive carefully.");
    }

    // Wind speed advice
    if (wind > 40) {
        advice.push("High winds expected, secure loose objects and be cautious outside.");
    } else if (wind > 20) {
        advice.push("It’s quite windy, wear a windproof jacket.");
    }

    console.log("Generated Advice:", advice);

    return advice.length > 0 ? advice.join(" ") : "No special advice for today.";
}



function populateAdvice(weatherData) {
    console.log("Populating advice with weather data:", weatherData);

    const days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    days.forEach((day, index) => {
        const startIndex = index * 24;

        const morningData = {
            temp: weatherData.hourly.temperature_2m[startIndex + 8] || null,
            humidity: weatherData.hourly.relative_humidity_2m[startIndex + 8] || null,
            uv: weatherData.hourly.uv_index[startIndex + 8] || null,
            visibility: weatherData.hourly.visibility[startIndex + 8] || null,
            wind: weatherData.hourly.wind_speed_10m[startIndex + 8] || null,
        };

        const afternoonData = {
            temp: weatherData.hourly.temperature_2m[startIndex + 14] || null,
            humidity: weatherData.hourly.relative_humidity_2m[startIndex + 14] || null,
            uv: weatherData.hourly.uv_index[startIndex + 14] || null,
            visibility: weatherData.hourly.visibility[startIndex + 14] || null,
            wind: weatherData.hourly.wind_speed_10m[startIndex + 14] || null,
        };

        const eveningData = {
            temp: weatherData.hourly.temperature_2m[startIndex + 20] || null,
            humidity: weatherData.hourly.relative_humidity_2m[startIndex + 20] || null,
            uv: weatherData.hourly.uv_index[startIndex + 20] || null,
            visibility: weatherData.hourly.visibility[startIndex + 20] || null,
            wind: weatherData.hourly.wind_speed_10m[startIndex + 20] || null,
        };

        console.log(`Updating ${day} advice`);
        console.log("Morning data:", morningData);
        console.log("Afternoon data:", afternoonData);
        console.log("Evening data:", eveningData);

        document.getElementById(`${day}-morning-advice`).textContent = generateAdvice(
            morningData.temp, morningData.humidity, morningData.uv, morningData.visibility, morningData.wind
        );
        document.getElementById(`${day}-afternoon-advice`).textContent = generateAdvice(
            afternoonData.temp, afternoonData.humidity, afternoonData.uv, afternoonData.visibility, afternoonData.wind
        );
        document.getElementById(`${day}-evening-advice`).textContent = generateAdvice(
            eveningData.temp, eveningData.humidity, eveningData.uv, eveningData.visibility, eveningData.wind
        );
    });
}




async function initAdvice() {
    const coordinates = await getCoordinates(locationName);
    if (!coordinates) return;
    
    const weatherData = await getWeatherData(coordinates.latitude, coordinates.longitude);
    if (!weatherData) return;
    
    populateAdvice(weatherData);
}

function setupTabSwitching() {
    document.querySelectorAll(".tabs ul li").forEach(tab => {
        tab.addEventListener("click", function () {
            document.querySelectorAll(".tabs ul li").forEach(t => t.classList.remove("is-active"));
            document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("is-active"));
            
            this.classList.add("is-active");
            document.getElementById(this.dataset.tab).classList.add("is-active");
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initAdvice();
    setupTabSwitching();
});

