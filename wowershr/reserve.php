<!DOCTYPE html>
<html lang="en">

<head>
    <title>WHR | Reservation</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reservationCSS.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!--Header-->
    <header>
        <img src="pictures/wowers.png" width="100" height="100">
        <div class="wowers-hotel-restaurant">WOWERS HOTEL & RESTAURANT</div>
        <div class="items">
            <span class="about-1">
                <a href="index.php" class="about_us_text">About</a>
            </span>
            <span class="rooms">
                <a href="rooms.html" class="rooms_text">Room</a>
            </span>
            <span class="dining">
                <a href="dining.html" class="dining_text">Dining</a>
            </span>
            <span class="reserve">
                <a href="reserve.php" class="reserve_text">Reserve</a>
            </span>
        </div>
    </header>

    <!--Reservation Code Input-->
    <div class="reservation-code">
        <span>Already have a reservation?</span>
        <form action="reservation.php" method="GET">
            <label for="reservationID">Reservation Code</label>
            <input type="text" id="reservationID" name="reservationID" placeholder="Enter Code">
            <input type="submit" value="Enter" name="reserved">
        </form>
    </div>

    <!-- Add Reservation -->
    <form id="reservationForm" action="insert.php" method="POST">
        <div>
            <h1>Reservation Form</h1>
        </div>
        <!--Input Text for Name-->
        <input type="text" id="name" name="name" placeholder="Enter Your Name" maxlength="30" required>
        <label for="name">Full Name</label><br>

        <!--Input Text for Email-->
        <input type="email" id="email" name="email" placeholder="Enter Your Email" maxlength="30" required>
        <label for="email">Email</label><br>

        <!--Input Tel for Contact Number-->
        <input type="tel" id="contactNumber" name="contactNumber" placeholder="Enter Your Number" maxlength="11" required>
        <label for="contactNumber">Phone Number</label><br>

        <!--Select type for Rooms-->
        <select id="roomType" name="roomID" required>
            <option value="" disabled selected>Choose Room Type</option>
            <option value="1">Single/Double Room</option>
            <option value="2">Deluxe Suite</option>
            <option value="3">Luxury Suite</option>
            <option value="4">Presidential Suite</option>
        </select>
        <label for="room">Room</label><br><br>

        <!--Date In and Out Function-->
        <div class="date-container">
            <label for="dateIN">Date In: </label>
            <input type="date" id="dateIN" name="dateIN" required>
            <label for="dateOUT">Date Out: </label>
            <input type="date" id="dateOUT" name="dateOUT" required>
        </div><br><br>

        <!--Reserve Button-->
        <div class="reservebutton">
            <input type="submit" value="Reserve" name="addReservation">
        </div>
    </form>

    <script>
        // JavaScript to handle date validation and form submission alert
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            var checkin = document.getElementById('dateIN'); // Check-in date input field
            var checkout = document.getElementById('dateOUT'); // Check-out date input field

            checkin.value = today; // Set the default check-in date to today
            checkin.setAttribute('min', today); // Set the minimum check-in date to today
            checkout.setAttribute('min', getNextDay(today)); // Set the minimum check-out date to the day after today

            // Event listener for changes in the check-in date field
            checkin.addEventListener('change', function() {
                var checkinDate = checkin.value; // Get the new check-in date
                checkout.setAttribute('min', getNextDay(checkinDate)); // Update the minimum check-out date
                if (checkout.value <= checkinDate) {
                    checkout.value = getNextDay(checkinDate); // Adjust check-out date if necessary
                }
            });
        });

        // Function to get the next day in YYYY-MM-DD format
        function getNextDay(date) {
            var nextDay = new Date(date);
            nextDay.setDate(nextDay.getDate() + 1); // Increment the date by one day
            return nextDay.toISOString().split('T')[0]; // Return the next day in YYYY-MM-DD format
        }

        // Function to get query parameter value by name
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Form submission alert
        var reservationID = getQueryParam('reservationID');
        if (reservationID) {
            alert('Your reservation has been successfully made! Your reservation ID is: ' + reservationID);
        }
    </script>

    <!--Footer-->
    <footer>
        <div class="wowershr-com">
            <p>wowershr.com</p>
        </div>
        <div class="contact-info-container">
            <span class="contact_us_text">Contact Us<br></span>
            <span class="contact_info">
                Pantropiko Island, Philippines <br>
                wowershr@gmail.com <br>
                +63 912 055 0056
            </span>
        </div>
    </footer>
</body>

</html>