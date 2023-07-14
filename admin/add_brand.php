<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data
    $name = $_POST['name'];
    $info = $_POST['info'];
    $category_id = $_POST['category_id'];
    $active = isset($_POST['active']) ? 1 : 0;

    $logo_path = '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $upload_dir = '../uploads/';
        $file_name = time() . '_' . basename($_FILES['logo']['name']);
        $upload_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path);
        $logo_path = 'uploads/' . $file_name;
    }

    $insert_query = "INSERT INTO brands (name, info, category_id, active, logo_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ssiss', $name, $info, $category_id, $active, $logo_path);
    mysqli_stmt_execute($stmt);

    $brand_id = mysqli_insert_id($conn);
    $user_types = isset($_POST['user_types']) ? $_POST['user_types'] : [];

    // Associate the brand with user types
    foreach ($user_types as $user_type_id) {
        $insert_query = "INSERT INTO user_brands (brand_id, user_type_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'ii', $brand_id, $user_type_id);
        mysqli_stmt_execute($stmt);
    }

    // Insert the brand fields
    $fields = $_POST['fields'];
    foreach ($fields as $field) {
        $label_id = $field['label_id'];
        $type = $field['type'];
        $order = $field['order'];

        $insert_query = "INSERT INTO brand_fields (brand_id, label_id, type, `order`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'iisi', $brand_id, $label_id, $type, $order);
        mysqli_stmt_execute($stmt);
    }

    eventLog($conn, "Brand added", 'addition', $user_id);

    header('Location: manage_brands.php');
    exit();
}

$label_presets_query = "SELECT * FROM label_presets";
$label_presets_result = mysqli_query($conn, $label_presets_query);
$label_presets_result = mysqli_fetch_all($label_presets_result, MYSQLI_ASSOC);

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);

$user_types_query = "SELECT * FROM user_type";
$user_types_result = mysqli_query($conn, $user_types_query);
$user_types_result = mysqli_fetch_all($user_types_result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Brand</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="add_edit_brand.css">
    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('logo-preview').src = e.target.result;
                    document.getElementById('logo-preview').style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add Brand</h1>
        <a href="manage_brands.php">Back to Manage Brands</a>
        <form action="add_brand.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="info">Info:</label>
            <textarea name="info" id="info" rows="5" required></textarea>
            <br>
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="active">Active:</label>
            <input type="checkbox" name="active" id="active">
            <br>
            <label for="logo">Logo:</label>
            <input type="file" name="logo" id="logo" onchange="previewLogo(this);">
            <br>
            <label>Logo Preview:</label>
            <img id="logo-preview" src="#" alt="Logo preview" style="display: none; max-width: 200px; max-height: 200px;">
            <br>
            <label>User Types:</label>
            <div id="user_types">
                <?php
                    foreach ($user_types_result as $user_type) {
                        echo '<input type="checkbox" id="user_type_' . $user_type['id'] . '" name="user_types[]" value="' . $user_type['id'] . '">';
                        echo '<label for="user_type_' . $user_type['id'] . '">' . $user_type['name'] . '</label><br>';
                    }
                ?>
            </div>
            <br>
            <label>Fields:</label>
            <div id="fields-container"></div>
            <button type="button" onclick="addField()">Add Field</button>
            <br>
            <button type="submit">Add Brand</button>
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
            labelSelect.name = `fields[${fieldCount}][label_id]`;
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
            typeSelect.name = `fields[${fieldCount}][type]`;

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
            orderInput.name = `fields[${fieldCount}][order]`;
            orderInput.value = fieldCount + 1;
            field.appendChild(orderInput);

            container.appendChild(field);

            fieldCount++;
        }
    </script>
</body>
</html>