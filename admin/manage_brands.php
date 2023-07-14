<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$search = isset($_GET['search']) ? $_GET['search'] : '';

$brands_query = "SELECT brands.id, brands.name, brands.info, brands.logo_path, brands.active, categories.name AS category_name FROM brands INNER JOIN categories ON brands.category_id = categories.id";

if ($search !== '') {
    $brands_query .= " WHERE brands.name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}

$brands_result = mysqli_query($conn, $brands_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Brands <a href="add_brand.php">Add New</a></h1>
        <a href="dashboard.php">Back to Dashboard</a>
        
        <form action="manage_brands.php" method="get">
            <input type="text" name="search" placeholder="Search brands" value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Info</th>
                    <th>Category</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($brands_result)): ?>
                    <tr>
                        <td><img src="<?php echo '../' . $row['logo_path']; ?>" alt="<?php echo $row['name']; ?>" width="50" height="50"></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['info']; ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td><?php echo $row['active'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <a href="edit_brand.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_brand.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this brand?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>