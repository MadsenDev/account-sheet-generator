<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $info = $_POST['info'];
    $category_id = $_POST['category_id'];
    $active = isset($_POST['active']) ? 1 : 0;

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $uploads_dir = '../uploads';
        $tmp_name = $_FILES['logo']['tmp_name'];
        $name_parts = pathinfo($_FILES['logo']['name']);
        $new_name = $id . '_' . time() . '.' . $name_parts['extension'];
        $logo_path = "$uploads_dir/$new_name";
        move_uploaded_file($tmp_name, $logo_path);

        $update_query = "UPDATE brands SET name = ?, info = ?, category_id = ?, active = ?, logo_path = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'ssiiis', $name, $info, $category_id, $active, $logo_path, $id);
    } else {
        $update_query = "UPDATE brands SET name = ?, info = ?, category_id = ?, active = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'ssiii', $name, $info, $category_id, $active, $id);
    }

    mysqli_stmt_execute($stmt);

    // Handle brand fields
    $submitted_fields = array_keys($_POST['brand_fields']);

    $old_fields_query = "SELECT id FROM brand_fields WHERE brand_id = ?";
    $stmt = mysqli_prepare($conn, $old_fields_query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $old_fields_result = mysqli_stmt_get_result($stmt);
    $old_fields = [];
    while ($row = mysqli_fetch_assoc($old_fields_result)) {
        $old_fields[] = $row['id'];
    }

    $deleted_fields = array_diff($old_fields, $submitted_fields);
    if (!empty($deleted_fields)) {
        $delete_query = "DELETE FROM brand_fields WHERE id IN (" . implode(', ', $deleted_fields) . ")";
        mysqli_query($conn, $delete_query);
    }

   // Re-add brand fields based on the submitted form
if (isset($_POST['brand_fields'])) {
    foreach ($_POST['brand_fields'] as $field_key => $field_value) {
        if (strpos($field_key, 'new_') === 0) {
            // This is a new field
            $insert_query = "INSERT INTO brand_fields (brand_id, label_id, type, `order`) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, 'iisi', $id, $field_value['label_id'], $field_value['type'], $field_value['order']);
            mysqli_stmt_execute($stmt);
        } else {
            // This is an existing field
            $update_query = "UPDATE brand_fields SET label_id = ?, type = ?, `order` = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, 'isii', $field_value['label_id'], $field_value['type'], $field_value['order'], $field_key);
            mysqli_stmt_execute($stmt);
        }
    }
}

// Deleting old user types
$delete_query = "DELETE FROM user_brands WHERE brand_id = ?";
$delete_stmt = mysqli_prepare($conn, $delete_query); 
if ($delete_stmt === false) {
    die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}

mysqli_stmt_bind_param($delete_stmt, 'i', $id);
mysqli_stmt_execute($delete_stmt);

// Adding new user types
if (isset($_POST['user_types'])) {
    foreach ($_POST['user_types'] as $user_type_id) {
        $insert_query = "INSERT INTO user_brands (brand_id, user_type_id) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query); 
        if ($insert_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
        }

        mysqli_stmt_bind_param($insert_stmt, 'ii', $id, $user_type_id);
        mysqli_stmt_execute($insert_stmt);
    }
}

eventLog($conn, "Edited brand $name", 'modification', $user_id);

    header('Location: manage_brands.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage_brands.php');
    exit();
}

$brand_id = $_GET['id'];

$brand_query = "SELECT * FROM brands WHERE id = ?";
$stmt = mysqli_prepare($conn, $brand_query);
mysqli_stmt_bind_param($stmt, 'i', $brand_id);
mysqli_stmt_execute($stmt);
$brand = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);

$brand_fields_query = "SELECT * FROM brand_fields WHERE brand_id = ? ORDER BY `order` ASC";
$stmt = mysqli_prepare($conn, $brand_fields_query);
mysqli_stmt_bind_param($stmt, 'i', $brand_id);
mysqli_stmt_execute($stmt);
$brand_fields_result = mysqli_stmt_get_result($stmt);
$brand_fields = [];
while ($row = mysqli_fetch_assoc($brand_fields_result)) {
    $brand_fields[] = $row;
}

$label_presets_query = "SELECT * FROM label_presets";
$label_presets_result = mysqli_query($conn, $label_presets_query);
$label_presets_result = mysqli_fetch_all($label_presets_result, MYSQLI_ASSOC);

$user_types_query = "SELECT user_type.* FROM user_type JOIN user_brands ON user_type.id = user_brands.user_type_id WHERE user_brands.brand_id = ?";
$user_types_stmt = mysqli_prepare($conn, $user_types_query);

if ($user_types_stmt === false) {
    die('Failed to prepare statement: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($user_types_stmt, 'i', $brand_id);
if (mysqli_stmt_execute($user_types_stmt)) {
    $user_types_result = mysqli_stmt_get_result($user_types_stmt);

    $user_types = [];
    while ($row = mysqli_fetch_assoc($user_types_result)) {
        $user_types[] = $row;
    }
} else {
    die('Failed to execute statement: ' . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Brand</h1>
        <a href="manage_brands.php">Back to Manage Brands</a>
        <form action="edit_brand.php?id=<?php echo $brand['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $brand['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $brand['name']; ?>" required>
            <br>
            <label for="info">Info:</label>
            <textarea name="info" id="info" rows="5" required><?php echo $brand['info']; ?></textarea>
            <br>
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($brand['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="active">Active:</label>
            <input type="checkbox" name="active" id="active" <?php echo $brand['active'] ? 'checked' : ''; ?>>
            <br>
            <label for="user_types">User Types:</label>
            <div id="user_types">
                <?php
                $user_types_list_query = "SELECT * FROM user_type";
                $user_types_list_result = mysqli_query($conn, $user_types_list_query);
                
                while($user_type = mysqli_fetch_assoc($user_types_list_result)): 
                    $isChecked = array_search($user_type['id'], array_column($user_types, 'id')) !== false ? 'checked' : '';
                ?>
                    <input type="checkbox" id="user_type_<?php echo $user_type['id']; ?>" name="user_types[]" value="<?php echo $user_type['id']; ?>" <?php echo $isChecked; ?>>
                    <label for="user_type_<?php echo $user_type['id']; ?>"><?php echo $user_type['name']; ?></label>
                <?php endwhile; ?>
            </div>
            <br>            
            <label>Fields:</label>
            <div id="fields-container">
                <!-- Dynamic Brand Fields -->
                <?php foreach ($brand_fields as $field): ?>
                    <div class="field" id="brand_field_<?php echo $field['id']; ?>">
                        <label for="label">Label:</label>
                        <select name="brand_fields[<?php echo $field['id']; ?>][label_id]" required>
                            <?php foreach ($label_presets_result as $label_preset): ?>
                                <option value="<?php echo $label_preset['id']; ?>" <?php echo ($field['label_id'] == $label_preset['id']) ? 'selected' : ''; ?>><?php echo $label_preset['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="brand_fields[<?php echo $field['id']; ?>][type]" value="<?php echo $field['type']; ?>">
                        <input type="number" name="brand_fields[<?php echo $field['id']; ?>][order]" value="<?php echo $field['order']; ?>">
                        <button type="button" onclick="removeField('brand_field_<?php echo $field['id']; ?>')">Remove</button>
                        <br>
                    </div>
                <?php endforeach; ?>
            <!-- End of Dynamic Brand Fields -->
            </div>
            <button type="button" onclick="addField()">Add Field</button>
            
            <button type="submit">Save Changes</button>
        </form>
    </div>
    <script>
    let fieldCount = 0;

    const labelPresets = <?php echo json_encode($label_presets_result); ?>;

    function addField() {
    const container = document.getElementById('fields-container');

    const field = document.createElement('div');
    field.classList.add('field');

    const label = document.createElement('label');
    label.textContent = 'Label:';
    field.appendChild(label);

    const labelSelect = document.createElement('select');
    labelSelect.name = `brand_fields[new_${fieldCount}][label_id]`;
    field.appendChild(labelSelect);

    labelPresets.forEach((labelPreset) => {
        const option = document.createElement('option');
        option.value = labelPreset.id;
        option.textContent = labelPreset.label;
        labelSelect.appendChild(option);
    });

    const typeLabel = document.createElement('label');
    typeLabel.textContent = 'Type:';
    field.appendChild(typeLabel);

    const typeSelect = document.createElement('select');
    typeSelect.name = `brand_fields[new_${fieldCount}][type]`;

    const textOption = document.createElement('option');
    textOption.value = 'text';
    textOption.textContent = 'Text';
    typeSelect.appendChild(textOption);

    const numberOption = document.createElement('option');
    numberOption.value = 'number';
    numberOption.textContent = 'Number';
    typeSelect.appendChild(numberOption);

    field.appendChild(typeSelect);

    const orderLabel = document.createElement('label');
    orderLabel.textContent = 'Order:';
    field.appendChild(orderLabel);

    const orderInput = document.createElement('input');
    orderInput.type = 'number';
    orderInput.name = `brand_fields[new_${fieldCount}][order]`;
    orderInput.value = fieldCount + 1;
    field.appendChild(orderInput);

    container.appendChild(field);

    fieldCount++;
}
    function removeField(fieldId) {
        const field = document.getElementById(fieldId);
        field.remove();
    }
</script>
</body>
</html>