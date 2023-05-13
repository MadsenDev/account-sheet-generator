<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

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
    $user_types = isset($_POST['user_types']) ? $_POST['user_types'] : [];
// Delete all existing rows for this brand
$delete_query = "DELETE FROM user_brands WHERE brand_id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

// Re-add rows based on the checkboxes
foreach ($user_types as $user_type_id) {
    $insert_query = "INSERT INTO user_brands (brand_id, user_type_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $user_type_id);
    mysqli_stmt_execute($stmt);
}

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

$user_types_query = "SELECT * FROM user_type";
$user_types_result = mysqli_query($conn, $user_types_query);
$user_types_result = mysqli_fetch_all($user_types_result, MYSQLI_ASSOC);

$brand_user_types_query = "SELECT user_type_id FROM user_brands WHERE brand_id = ?";
$stmt = mysqli_prepare($conn, $brand_user_types_query);
mysqli_stmt_bind_param($stmt, 'i', $brand_id);
mysqli_stmt_execute($stmt);
$brand_user_types_result = mysqli_stmt_get_result($stmt);
$brand_user_types = [];
while ($row = mysqli_fetch_assoc($brand_user_types_result)) {
    $brand_user_types[] = $row['user_type_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('logo-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Brand</h1>
        <a href="manage_brands.php">Back to Manage Brands</a>
        <form action="edit_brand.php" method="POST" enctype="multipart/form-data">
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
            <label for="logo">Logo:</label>
            <input type="file" name="logo" id="logo" onchange="previewLogo(this);">
            <br>
            <label>Current Logo:</label>
            <img src="<?php echo '../' . $brand['logo_path']; ?>" alt="<?php echo $brand['name']; ?>" width="100" height="100">
            <br>
            <label>New Logo Preview:</label>
            <img id="logo-preview" src="#" alt="Logo preview" width="100" height="100" style="display: none;">
            <br>
            <label for="active">Active:</label>
            <input type="checkbox" name="active" id="active" <?php echo $brand['active'] ? 'checked' : ''; ?>>
            <br>
            <label>User Types:</label>
            <?php
                foreach ($user_types_result as $user_type) {
                    $checked = in_array($user_type['id'], $brand_user_types) ? 'checked' : '';
                    echo '<input type="checkbox" name="user_types[]" value="' . $user_type['id'] . '" ' . $checked . '> ' . $user_type['name'] . '<br>';
                }
            ?>
            <br>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>