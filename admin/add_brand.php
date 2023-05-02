<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

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

    header('Location: manage_brands.php');
    exit();
}

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Brand</title>
    <link rel="stylesheet" href="admin.css">
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
            <button type="submit">Add Brand</button>
        </form>
    </div>
</body>
</html>