<?php
session_start();
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us</title>
  <link rel="stylesheet" href="bulma.css">
  <style>
    body {
      background-color: #f5f5f5;
    }
    .hero-body {
      background-image: url('map.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      padding: 10rem 0;
    }
    .content-box {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 50%;
      max-width: 1000px;
      margin: 0 auto;
    }
    .notification {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 1rem;
  margin: 1rem;
  border-radius: 5px;
  z-index: 1000;
    } 

    .is-hidden {
      display: none;
    }
    @media (max-width: 768px) {
      .content-box {
        width: 90% !important;
        padding: 1.5rem !important;
      }
    }
  </style>
</head>

<body>

<section class="hero is-primary has-background" style="background-image: url('map.jpg'); background-size: cover; background-position: center; padding: 5rem 0;">
  <div class="content-box has-text-light">
    <h1 class="title has-text-light">Contact Us</h1>

    <form id="contact-form">
      <div class="field">
        <label class="label has-text-light">Name</label>
        <div class="control">
          <input class="input" type="text" name="name" placeholder="Enter Your Name" required>
        </div>
      </div>

      <div class="field">
        <label class="label has-text-light">Username</label>
        <div class="control">
          <input class="input" type="text" name="username" placeholder="Enter Your Username" required>
        </div>
      </div>

      <div class="field">
        <label class="label has-text-light">Email</label>
        <div class="control">
          <input class="input" type="email" name="email" placeholder="Enter Your Email" required>
        </div>
      </div>

      <div class="field">
        <label class="label has-text-light">Subject</label>
        <div class="control">
          <div class="select">
            <select name="subject">
              <option>Enquiry for website</option>
              <option>Contact Health and Advice Group</option>
              <option>Other</option>
            </select>
          </div>
        </div>
      </div>

      <div class="field">
        <label class="label has-text-light">Message</label>
        <div class="control">
          <textarea class="textarea" name="message" placeholder="Your message" required></textarea>
        </div>
      </div>

      <div class="field">
        <div class="control">
          <label class="checkbox">
            <input type="checkbox" name="agreed_to_terms" required>
            I agree to the <a href="#">terms and conditions</a>
          </label>
        </div>
      </div>

      <div class="field is-grouped">
        <div class="control">
          <button class="button is-link" type="submit">Submit</button>
        </div>
      </div>
    </form>

    <!-- Popup Message Container -->

  </div>
</section>
<div id="popup-message" class="notification is-hidden"></div>

<script>
document.getElementById("contact-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);

    fetch("submit_contact.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        let popup = document.getElementById("popup-message");
        popup.innerText = data.message;
        popup.classList.remove("is-hidden");
        popup.classList.add(data.status === "success" ? "is-success" : "is-danger");

        // Hide popup after 3 seconds
        setTimeout(() => {
            popup.classList.add("is-hidden");
            popup.classList.remove("is-success", "is-danger");
        }, 3000);

        // Clear the form on success
        if (data.status === "success") {
            document.getElementById("contact-form").reset();
        }
    })
    .catch(error => console.error("Error:", error));
});
</script>

<?php require "footer.php"; ?>

</body>
</html>
