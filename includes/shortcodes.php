<?php
// Marathon Registration Form Shortcode
function kandara_registration_form() {
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="kandara-form">
        <input type="hidden" name="action" value="kandara_process_form">
        <?php wp_nonce_field('kandara-registration-form', 'kandara-registration-nonce'); ?>
        <div class="container text-start">
            <h2>Marathon Registration</h2>
            <!-- Form Fields -->
            <div class="row align-items-start">
                <div class="form-group col-6">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="id_no">ID No</label>
                    <input type="text" name="id_no" id="id_no" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="mpesa_phone">Mpesa Phone Number</label>
                    <input type="text" name="mpesa_phone" id="mpesa_phone" class="form-control">
                </div>
            </div>
            <hr style="width:100%;text-align:left;margin:20px 0px 20px 0px;">
            <div class="row align-items-start">
                <div class="form-group col-4">
                    <label for="runner_category">Runner Category</label>
                    <select name="runner_category" id="runner_category" class="form-select" required>
                        <option value="Adult">Adult</option>
                        <option value="Children">Children</option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="race">Preferred Race</label>
                    <select name="race" id="race" class="form-select" required>
                        <option value="21km">21km</option>
                        <option value="10km">10km</option>
                        <option value="5km">5km</option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="tshirt_size">Preferred T-Shirt Size</label>
                    <select name="tshirt_size" id="tshirt_size" class="form-select" required>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
            </div>
            <hr style="width:100%;text-align:left;margin:20px 0px 20px 0px;">
            <div class="form-group">
                <label for="pickup_point">Pick up Point for T-Shirt</label>
                <select name="pickup_point" id="pickup_point" class="form-select" required>
                    <option value="Nairobi (Pension Towers, Loita street 2nd Floor)">Nairobi (Pension Towers, Loita street 2nd Floor)</option>
                    <option value="Kandara (Kamurugu NTK Sacco Office)">Kandara (Kamurugu NTK Sacco Office)</option>
                    <option value="Thika (Arrow Dental Centre. Thika Gateway Plaza, Gatitu Next to Total Petrol Station)">Thika (Arrow Dental Centre. Thika Gateway Plaza, Gatitu Next to Total Petrol Station)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div class="form-check">
                    <input type="radio" name="gender" id="male" value="Male" class="form-check-input" required>
                    <label for="male" class="form-check-label">Male</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="gender" id="female" value="Female" class="form-check-input" required>
                    <label for="female" class="form-check-label">Female</label>
                </div>
            </div>
            <div class="form-check form-switch">
                <input type="checkbox" name="email_updates" id="email_updates" class="form-check-input" role="switch">
                <label for="email_updates" class="form-check-label">I would like to receive email updates regarding the Marathon</label>
            </div>
            <div class="form-check form-switch">
                <input type="checkbox" name="whatsapp_updates" id="whatsapp_updates" class="form-check-input" role="switch">
                <label for="whatsapp_updates" class="form-check-label">I would like to join the race WhatsApp Community for updates and training tips</label>
            </div>
            <div class="form-check form-switch">
                <input type="checkbox" name="terms_conditions" id="terms_conditions" class="form-check-input" role="switch" required>
                <label for="terms_conditions" class="form-check-label">I acknowledge that I have completely read and fully understood, and do hereby accept the terms and conditions of The Kandara Education Run.</label>
            </div>
            <input type="submit" name="kandara_register" value="Submit and Proceed to Make Payment" class="btn btn-primary">
        </div>
    </form>
    <hr style="width:100%;text-align:left;margin:40px 0px 20px 0px;">
    <?php
    return ob_get_clean();
}
add_shortcode('kandara_registration_form', 'kandara_registration_form');

// Volunteer Registration Form Shortcode
function kandara_volunteer_registration_form() {
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="kandara-form">
        <input type="hidden" name="action" value="kandara_process_volunteer_form">
        <?php wp_nonce_field('kandara-volunteer-form', 'kandara-volunteer-nonce'); ?>
        <div class="container text-start">
            <h2>Volunteer Registration</h2>
            <!-- Form Fields -->
            <div class="row align-items-start">
                <div class="form-group col-6">
                    <label for="first_name_volunteer">Full Names</label>
                    <input type="text" name="first_name_volunteer" id="first_name" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label>please select your Preferred Volunteer Role</label>
                    <select name="volunteer_role" id="volunteer_role" class="form-select" required>
                        <option value="Clean-up Crew">Clean-up Crew</option>
                        <option value="Course Marshals">Course Marshals</option>
                        <option value="Cyclists">Cyclists</option>
                        <option value="Entertainment team (MC, DJ, Warm-up Hypeman)">Entertainment team (MC, DJ, Warm-up Hypeman)</option>
                        <option value="Information Ambassadors">Information Ambassadors</option>
                        <option value="Logistics and Setup Crew">Logistics and Setup Crew</option>
                        <option value="Logistics Cars &Drivers">Logistics Cars &Drivers</option>
                        <option value="Water Stations Crew">Water Stations Crew</option>
                        <option value="T-shirt Pickup teams">T-shirt Pickup teams</option>
                        <option value="Security Officers">Security Officers</option>
                        <option value="Medical Team">Medical Team</option>
                        <option value="Medals Team">Medals Team</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="email_volunteer">Email</label>
                    <input type="email" name="email_volunteer" id="email" class="form-control" required>
                </div>
                <div class="form-group col-6">
                    <label for="phone_volunteer">Phone Number</label>
                    <input type="text" name="phone_volunteer" id="phone" class="form-control" required>
                </div>
            </div>
            <div class="row align-items-start">
                <div class="form-group col-6">
                        <label>Gender</label>
                        <div class="form-check">
                            <input type="radio" name="gender" id="male" value="Male" class="form-check-input" required>
                            <label for="male" class="form-check-label">Male</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="gender" id="female" value="Female" class="form-check-input" required>
                            <label for="female" class="form-check-label">Female</label>
                        </div>
                    </div>
                <div class="form-group col-6">
                    <label>please note all fields are required *</label>
                    <input type="submit" name="kandara_volunteer_register" value="Submit to Volunteer" class="btn btn-primary">
                </div>  
            </div> 
        </div>
    </form>
    <hr style="width:100%;text-align:left;margin:40px 0px 20px 0px;">
    <?php
    return ob_get_clean();
}
add_shortcode('kandara_volunteer_registration_form', 'kandara_volunteer_registration_form');

?>