<?php
function kandara_add_admin_menu() {
    add_menu_page('Kandara Registrations', 'Kandara Registrations', 'manage_options', 'kandara-registrations', 'kandara_display_admin_page', 'dashicons-list-view', 6);
    add_submenu_page('kandara-registrations', 'Volunteer Registrations', 'Volunteers', 'manage_options', 'kandara-volunteers', 'kandara_display_volunteers_page');
}
add_action('admin_menu', 'kandara_add_admin_menu');

function kandara_display_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_registrations';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h2>Kandara Marathon Registrations</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="deleteForm">
            <input type="hidden" name="action" value="kandara_delete_rows">
            <input type="hidden" name="table" value="kandara_registrations">
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
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
                        <th>Bib NO:</th>
                        <th>Email Updates</th>
                        <th>WhatsApp Updates</th>
                        <th>Terms & Conditions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?php echo esc_html($row->id); ?>"></td>
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
                        <td><?php echo esc_html($row->bib_no); ?></td>
                        <td><?php echo $row->email_updates ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $row->whatsapp_updates ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $row->terms_conditions ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure you want to delete the selected rows?');">Delete Selected</button>
            <button type="submit" class="button button-primary" formaction="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="action" value="kandara_export_csv">Export to CSV</button>
        
        </form>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var checkAll = document.getElementById('checkAll');
                    if (checkAll) {
                        checkAll.addEventListener('change', function() {
                            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="ids[]"]');
                            checkboxes.forEach(function(checkbox) {
                                checkbox.checked = checkAll.checked;
                            });
                        });
                    }
                });
            </script>
    </div>
    <?php
}

function kandara_display_volunteers_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'kandara_volunteers';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h2>Kandara Marathon Volunteer Registrations</h2>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="deleteForm">
            <input type="hidden" name="action" value="kandara_delete_rows">
            <input type="hidden" name="table" value="kandara_volunteers">
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>ID</th>
                        <th>Full Names</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Volunteer Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row) { ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?php echo esc_html($row->id); ?>"></td>
                        <td><?php echo esc_html($row->id); ?></td>
                        <td><?php echo esc_html($row->first_name); ?></td>
                        <td><?php echo esc_html($row->email); ?></td>
                        <td><?php echo esc_html($row->phone); ?></td>
                        <td><?php echo esc_html($row->gender); ?></td>
                        <td><?php echo esc_html($row->volunteer_role); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit" class="button button-secondary" onclick="return confirm('Are you sure you want to delete the selected rows?');">Delete Selected</button>
            <button type="submit" class="button button-primary" formaction="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="action" value="kandara_export_csv">Export to CSV</button>

        </form>

            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var checkAll = document.getElementById('checkAll');
                    if (checkAll) {
                        checkAll.addEventListener('change', function() {
                            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="ids[]"]');
                            checkboxes.forEach(function(checkbox) {
                                checkbox.checked = checkAll.checked;
                            });
                        });
                    }
                });
            </script>
    </div>
    <?php
}

function kandara_export_csv() {
    if (!isset($_POST['table'])) {
        wp_die('No table specified.');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . sanitize_text_field($_POST['table']);

    if ($table_name === $wpdb->prefix . 'kandara_registrations') {
        $filename = 'kandara_registrations_' . date('Y-m-d') . '.csv';
        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        $columns = array('ID', 'First Name', 'Last Name', 'ID No', 'Email', 'Phone', 'Mpesa Phone', 'Runner Category', 'Race', 'T-Shirt Size', 'Pickup Point', 'Gender', 'Email Updates', 'WhatsApp Updates', 'Terms & Conditions');
    } elseif ($table_name === $wpdb->prefix . 'kandara_volunteers') {
        $filename = 'kandara_volunteers_' . date('Y-m-d') . '.csv';
        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        $columns = array('ID', 'Full Name', 'Last Name', 'Email', 'Phone', 'Gender', 'Volunteer Role');
    } else {
        wp_die('Invalid table specified.');
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    $output = fopen('php://output', 'w');
    fputcsv($output, $columns);

    foreach ($results as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
add_action('admin_post_kandara_export_csv', 'kandara_export_csv');
add_action('admin_post_nopriv_kandara_export_csv', 'kandara_export_csv');

function kandara_delete_rows() {
    if (!isset($_POST['ids']) || !isset($_POST['table'])) {
        wp_die('No rows or table specified.');
    }

    global $wpdb;
    $ids = array_map('intval', $_POST['ids']);
    $table_name = $wpdb->prefix . sanitize_text_field($_POST['table']);

    foreach ($ids as $id) {
        $wpdb->delete($table_name, array('id' => $id));
    }

    wp_redirect(wp_get_referer());
    exit;
}
add_action('admin_post_kandara_delete_rows', 'kandara_delete_rows');
add_action('admin_post_nopriv_kandara_delete_rows', 'kandara_delete_rows');
?>

