<?php
function kandara_add_admin_menu() {
    add_menu_page('Kandara Registrations', 'Kandara Registrations', 'manage_options', 'kandara-registrations', 'kandara_display_admin_page', 'dashicons-list-view', 6);
}
add_action('admin_menu', 'kandara_add_admin_menu');

function kandara_display_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_registrations';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h2>Kandara Marathon Registrations</h2>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>ID No</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mpesa Phone</th>
                    <th>Runner Category</th>
                    <th>Race</th>
                    <th>T-Shirt Size</th>
                    <th>Pickup Point</th>
                    <th>Gender</th>
                    <th>Email Updates</th>
                    <th>WhatsApp Updates</th>
                    <th>Terms & Conditions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) { ?>
                <tr>
                    <td><?php echo esc_html($row->id); ?></td>
                    <td><?php echo esc_html($row->first_name); ?></td>
                    <td><?php echo esc_html($row->last_name); ?></td>
                    <td><?php echo esc_html($row->id_no); ?></td>
                    <td><?php echo esc_html($row->email); ?></td>
                    <td><?php echo esc_html($row->phone); ?></td>
                    <td><?php echo esc_html($row->mpesa_phone); ?></td>
                    <td><?php echo esc_html($row->runner_category); ?></td>
                    <td><?php echo esc_html($row->race); ?></td>
                    <td><?php echo esc_html($row->tshirt_size); ?></td>
                    <td><?php echo esc_html($row->pickup_point); ?></td>
                    <td><?php echo esc_html($row->gender); ?></td>
                    <td><?php echo esc_html($row->email_updates); ?></td>
                    <td><?php echo esc_html($row->whatsapp_updates); ?></td>
                    <td><?php echo esc_html($row->terms_conditions); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}
