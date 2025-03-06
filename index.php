<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $_SESSION['location'] = $_GET['location']; // Store location in session
}
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";
$conn = getDB();
?>



<navbar class="php"></navbar>


  <section class="hero is-primary has-background"
    style="background-image: url(map.jpg); background-size: cover; background-position: center; padding: 10rem 0; ">
    <div class="hero-head has-text-centered">
      <div class="container has-text-light">
        <div style="
                background-color: rgba(0, 0, 0, 0.6); 
                padding: 2rem; 
                border-radius: 8px; 
                display: inline-block; 
                max-width: 90%; 
                margin: 0 auto;">
        <h1 class="title has-text-light">
          WeatherWell
        </h1>
        <p class="subtitle has-text-light">
          Never let mother nature get the better of you.
        </p>
      </div>
    </div>
    <div class="hero-body">
      <div class="field has-text-centered">
        <div class="control" style="max-width: 800px; margin: 0 auto;">
          <input class="input animate__animated animate__pulse" type="text" placeholder="Enter your location" id="location-search">
          <div id="autocomplete-results" class="box" style="display: none; position: absolute; z-index: 100;"></div>
          <form action="dashboard.php" method="get" onsubmit="return setLocationValue()" style="margin-top: 1rem;">
  <input type="hidden" name="location" id="hidden-location">
  <button class="button is-primary" type="submit">Check Weather</button>
</form>

<script>
  function setLocationValue() {
    const searchInput = document.getElementById("location-search").value.trim();
    if (searchInput === "") {
      alert("Please enter a valid location.");
      return false; // Prevent form submission if empty
    }
    document.getElementById("hidden-location").value = searchInput;
    return true; // Allow form submission
  }

  
</script>

        </div>
      </div>
    </div>
    </div>
  </section>

  <?php require "footer.php"; ?>

  <script>
  document.getElementById("location-search").addEventListener("input", async function () {
    const query = this.value.trim();
    const resultsBox = document.getElementById("autocomplete-results");
    resultsBox.innerHTML = "";
    if (query.length < 3) {
      resultsBox.style.display = "none";
      return;
    }

    try {
      const response = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(query)}&count=5&language=en&format=json`);
      const data = await response.json();

      if (data.results && data.results.length > 0) {
        resultsBox.style.display = "block";
        data.results.forEach(result => {
          const city = result.name;
          const country = result.country;
          const displayText = `${city}, ${country}`;

          const item = document.createElement("div");
          item.textContent = displayText;
          item.classList.add("dropdown-item");
          item.style.cursor = "pointer";
          item.style.padding = "10px";
          item.style.borderBottom = "1px solid #ddd";

          item.addEventListener("click", function () {
            document.getElementById("location-search").value = city;
            document.getElementById("hidden-location").value = city; // Ensure correct submission
            resultsBox.style.display = "none";
          });

          resultsBox.appendChild(item);
        });
      } else {
        resultsBox.style.display = "none";
      }
    } catch (error) {
      console.error("Error fetching autocomplete results:", error);
    }
  });
</script>

</body>
</html>