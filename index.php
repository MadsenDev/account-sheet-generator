<?php
require_once 'db.php';
require_once 'func.php';
require_once 'admin/functions.php';

session_start();
$loggedIn = isset($_SESSION['user_id']);

$ip_address = $_SERVER['REMOTE_ADDR'];

// You can use an API to get the location data, for example, ipinfo.io
$location = file_get_contents("http://ipinfo.io/{$ip_address}/json");
$locationData = json_decode($location, true);
$location = isset($locationData['city']) ? $locationData['city'] . ', ' . $locationData['region'] . ', ' . $locationData['country'] : 'Unknown location';

if (isUniqueDailyVisit()) {
    $currentDate = date('Y-m-d');
    // Insert a new row for today's unique visit
    $sql = "INSERT INTO unique_daily_visits (visit_date, ip_address, location) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $currentDate, $ip_address, $location);
    $stmt->execute();
}

if (isIpBlocked($conn, $ip_address)) {
  eventLog($conn, "Access blocked for: $ip_address");
  // Redirect to a page informing them they are blocked, or elsewhere
  header('Location: blocked.php');
  exit();
}

$user_type_query = "SELECT id, name, title FROM user_type";
$result = mysqli_query($conn, $user_type_query);

// Get all languages
$language_query = "SELECT id, language FROM languages";
$language_result = mysqli_query($conn, $language_query);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Printable Account Sheets</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.png">
  </head>
  <body>
    <div id="overlay">
    <div id="overlay-content">
      <h2>Add Custom Brand</h2>
      <form>
        <label for="name">Name:</label>
        <input type="text" id="name" required>
        <br>
        <label for="logo">Logo:</label>
        <input type="file" id="logo" accept="image/*" required>
        <br>
        <label>Fields:</label>
        <div id="fields-container"></div>
        <button type="button" onclick="addField()">Add Field</button>
        <br>
        <button type="button" onclick="submitForm()">Add Brand</button>
      </form>
      <div id="close-overlay" class="big-button">
        <label for="close-overlay">Close</label>
      </div>
    </div>
  </div>
    <div id="wrapper">
      <div id="left-sidebar">
        <div id="site-logo">
          <img src="images/logo.png" alt="Printable Account Sheets logo">
          <h1>Printable Account Sheets</h1>
        </div>
        <div id="print-button" class="big-button">
          <label for="print">Print</label>
        </div>
        <div id="open-overlay" class="big-button">
          <label for="open-overlay">Custom Brand (WIP)</label>
        </div>
        <div id="clear-sections" class="big-button">
          <label for="clear-sections">Clear Sections</label>
        </div>
        <div id="uploader-left" class="big-button">
          <label for="logo-input-left">Upload Logo (Left)</label>
          <input type="file" id="logo-input-left">
        </div>
        <div id="uploader-right" class="big-button">
          <label for="logo-input-right">Upload Logo (Right)</label>
          <input type="file" id="logo-input-right">
        </div>
        <div id="clear-logos" class="big-button">
          <label for="clear-logos">Clear Logos</label>
        </div>
        <div id="add-watermark-button" class="big-button">
          <label for="add-watermark-button">Upload Watermark (WIP)</label>
          <input type="file" id="logo-input-right">
        </div>
        <div id="clear-watermark" class="big-button">
          <label for="clear-watermark">Clear Watermark</label>
        </div>
        <div id="language-selection">
    <select id="language-select">
        <?php
        $english_row = [];
        while ($row = mysqli_fetch_assoc($language_result)) {
            // If the language is English, store it in a variable and continue to the next iteration
            if ($row['language'] == 'English') {
                $english_row = $row;
                continue;
            }
            echo "<option value='{$row['id']}'>{$row['language']}</option>";
        }

        // If English was found, prepend it to the select
        if (!empty($english_row)) {
            echo "<option value='{$english_row['id']}' selected='selected'>{$english_row['language']}</option>";
        }
        ?>
    </select>
</div>
      <!--Add radio buttons to select who you're using the web-site as-->
      <div id="user-type">
        <label for="user-type">User Type</label>
        <div id="user-type-buttons">
          <?php
          $first = true;
          while ($row = mysqli_fetch_assoc($result)) {
              $checked = $first ? 'checked' : '';
              echo "<input type='radio' name='user-type' id='user-type-{$row['id']}' value='{$row['id']}' $checked>";
              echo "<label for='user-type-{$row['id']}'>{$row['name']}</label><br>";
              $first = false;
          }
          ?>
        </div>
      </div>
      </div>
      <div id="a4-area">
        <div id="header">
          <h1 id="header-text">Account Information</h1>
        </div>
        <div id="a4-content">
          <!-- A4 format area goes here -->
        </div>
        <!-- A4 format area goes here -->
        <div id="sections-container"></div>
        <div id="logo-left"></div>
        <div id="logo-right"></div>
      </div>
      <div id="right-sidebar">
        <div id="search">
          <form id="search-form">
            <input type="text" id="search-input" placeholder="Search brands...">
          </form>
        </div>
        <div id="categories">
          <div id="category-buttons">
            <button id="category-select-all">Select All</button>
            <button id="category-unselect-all">Unselect All</button>
          </div>
          <?php
          // Get all categories
          $sqlCategories = "SELECT id, name FROM categories ORDER BY name ASC";
          $stmtCategories = mysqli_prepare($conn, $sqlCategories);
          if ($stmtCategories === false) {
              echo mysqli_error($conn);
          }
          else {
              mysqli_stmt_execute($stmtCategories);
              $resultCategories = mysqli_stmt_get_result($stmtCategories);

              // Display category checkboxes
              while ($rowCategories = mysqli_fetch_assoc($resultCategories)) {
                  $categoryId = $rowCategories["id"];
                  $categoryName = $rowCategories["name"];

                  echo "<input type='checkbox' name='category_filter' id='$categoryName' value='$categoryId' checked>";
                  echo "<label for='$categoryName'>$categoryName</label>";
              }

              mysqli_free_result($resultCategories);
              mysqli_stmt_close($stmtCategories);
          }
          ?>

        </div>
        <div id="brand-container" style="height: 640px; overflow: auto;">
          <?php
          // Get all brands
          $sqlBrands = "SELECT b.id, b.name, b.logo_path, b.category_id, c.name AS category_name, b.info FROM brands b JOIN categories c ON b.category_id = c.id ORDER BY c.name ASC, b.name ASC";
          $stmtBrands = mysqli_prepare($conn, $sqlBrands);
          if ($stmtBrands === false) {
          echo mysqli_error($conn);
          }
          else {
          mysqli_stmt_execute($stmtBrands);
          $resultBrands = mysqli_stmt_get_result($stmtBrands);

          // Display brand buttons
          while ($rowBrands = mysqli_fetch_assoc($resultBrands)) {
          $brandId = $rowBrands["id"];
          $brandName = $rowBrands["name"];
          $brandLogo = $rowBrands["logo_path"];
          $brandCategoryId = $rowBrands["category_id"];
          $brandCategoryName = $rowBrands["category_name"];
          $brandInfo = $rowBrands["info"];

          echo "<a href='#' class='brand-button' id='$brandName' data-category='$brandCategoryId' data-id='$brandId'>";
          echo "<img src='$brandLogo' alt='$brandName'>";
          echo "$brandName";
          echo "</a>";
          }

          mysqli_free_result($resultBrands);
          mysqli_stmt_close($stmtBrands);
          }

          mysqli_close($conn);
          ?>
        </div>
        

        <script>
        // Get all category checkboxes and brand buttons
        const categoryCheckboxes = document.querySelectorAll('input[name="category_filter"]');
        const brandButtons = document.querySelectorAll('.brand-button');

        // Function to hide/show brand buttons based on checked categories
        const updateBrandButtonDisplay = () => {
        // Get the checked category IDs
        const checkedCategoryIds = Array.from(categoryCheckboxes)
        .filter(function(categoryCheckbox) {
        return categoryCheckbox.checked;
        })
        .map(function(categoryCheckbox) {
        return categoryCheckbox.value;
        });

        // Hide/show brand buttons based on category ID
        brandButtons.forEach(function(brandButton) {
        const brandCategoryId = brandButton.dataset.category;
        if (checkedCategoryIds.includes(brandCategoryId)) {
        brandButton.style.display = '';
        } else {
        brandButton.style.display = 'none';
        }
        });
        };

        // Add event listeners to category checkboxes
        categoryCheckboxes.forEach(function(categoryCheckbox) {
        categoryCheckbox.addEventListener('change', function() {
        updateBrandButtonDisplay();
        });
        });

        // Get the select all and unselect all buttons
        const selectAllBtn = document.querySelector("#category-select-all");
        const unselectAllBtn = document.querySelector("#category-unselect-all");

        // Add event listeners to the buttons
        selectAllBtn.addEventListener("click", () => {
        categoryCheckboxes.forEach((checkbox) => {
        checkbox.checked = true;
        });
        updateBrandButtonDisplay(); // call the function to update brand buttons display
        });

        unselectAllBtn.addEventListener("click", () => {
        categoryCheckboxes.forEach((checkbox) => {
        checkbox.checked = false;
        });
        updateBrandButtonDisplay(); // call the function to update brand buttons display
        });
        </script>
      </div>

    </div>
    <footer>
      <p><b>No account data is stored on this website.</b> Go to the source at <a href="https://github.com/MadsenDev/account-sheet-generator" target="_blank">GitHub</a>.</p>
      <p>Admin? <a href="<?php echo $loggedIn ? 'admin/dashboard.php' : 'admin/login.php'; ?>">
    <?php echo $loggedIn ? 'Go to Dashboard' : 'Log in here'; ?>
</a>.</p>
<p>© 2023 Madsen Utvikling | Org nr: 927840480</p>
</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script src="script.js"></script>
    <script src="overlay.js"></script>
  </body>
</html>