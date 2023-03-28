<!DOCTYPE html>
<html>
  <head>
    <title>Printable Account Sheets</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/favicon.png">
  </head>
  <body>
    <div id="wrapper">
      <div id="left-sidebar">
        <div id="site-logo">
          <img src="images/logo.png" alt="Printable Account Sheets logo">
          <h1>Printable Account Sheets</h1>
        </div>
        <div id="print-button">
          <label for="print">Print</label>
        </div>
        <div id="clear-sections">
          <label for="clear-sections">Clear Sections</label>
        </div>
        <div id="uploader-left">
          <label for="logo-input-left">Upload Logo (Left)</label>
          <input type="file" id="logo-input-left">
        </div>
        <div id="uploader-right">
          <label for="logo-input-right">Upload Logo (Right)</label>
          <input type="file" id="logo-input-right">
        </div>
        <!--Add radio buttons to select who you're using the web-site as-->
        <div id="user-type">
          <label for="user-type">User Type</label>
          <div id="user-type-buttons">
            <input type="radio" name="user-type" id="user-type-universal" value="universal" checked>
            <label for="user-type-universal">Universal</label><br>
            <input type="radio" name="user-type" id="user-type-elkjop" value="elkjop">
            <label for="user-type-elkjop">Elkjøp (NO)</label>
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
        <div id="brand-buttons">

          <nav class="main-menu">
            <ul>
              <li>
                <a href="#" class="brand-button">
                  E-mail
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Google">
                    <img src="images/google-logo.png" alt="Google">
                    Google
                  </a></li>
                  <li><a href="#" class="brand-button" id="Microsoft">
                    <img src="images/microsoft-logo.png" alt="Microsoft">
                    Microsoft
                  </a></li>
                  <li><a href="#" class="brand-button" id="Apple">
                    <img src="images/apple-logo.png" alt="Apple">
                    Apple
                  </a></li>
                  <li><a href="#" class="brand-button" id="Online">
                    <img src="images/telenor-logo.png" alt="Online">
                    Online
                  </a></li>
                  <li><a href="#" class="brand-button" id="Yahoo">
                    <img src="images/yahoo-logo.png" alt="Yahoo">
                    Yahoo
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Social Media
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Facebook">
                    <img src="images/facebook-logo.png" alt="Facebook">
                    Facebook
                  </a></li>
                  <li><a href="#" class="brand-button" id="Instagram">
                    <img src="images/instagram-logo.png" alt="Instagram">
                    Instagram
                  </a></li>
                  <li><a href="#" class="brand-button" id="Twitter">
                    <img src="images/twitter-logo.png" alt="Twitter">
                    Twitter
                  </a></li>
                  <li><a href="#" class="brand-button" id="LinkedIn">
                    <img src="images/linkedin-logo.png" alt="LinkedIn">
                    LinkedIn
                  </a></li>
                  <li><a href="#" class="brand-button" id="Pinterest">
                    <img src="images/pinterest-logo.png" alt="Pinterest">
                    Pinterest
                  </a></li>
                  <li><a href="#" class="brand-button" id="Snapchat">
                    <img src="images/snapchat-logo.png" alt="Snapchat">
                    Snapchat
                  </a></li>
                  <li><a href="#" class="brand-button" id="WhatsApp">
                    <img src="images/whatsapp-logo.png" alt="WhatsApp">
                    WhatsApp
                  </a></li>
                  <li><a href="#" class="brand-button" id="YouTube">
                    <img src="images/youtube-logo.png" alt="YouTube">
                    YouTube
                  </a></li>
                  <li><a href="#" class="brand-button" id="Twitch">
                    <img src="images/twitch-logo.png" alt="Twitch">
                    Twitch
                  </a></li>
                  <li><a href="#" class="brand-button" id="Reddit">
                    <img src="images/reddit-logo.png" alt="Reddit">
                    Reddit
                  </a></li>
                  <li><a href="#" class="brand-button" id="Tumblr">
                    <img src="images/tumblr-logo.png" alt="Tumblr">
                    Tumblr
                  </a></li>
                  <li><a href="#" class="brand-button" id="Vimeo">
                    <img src="images/vimeo-logo.png" alt="Vimeo">
                    Vimeo
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Shopping
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Amazon">
                    <img src="images/amazon-logo.png" alt="Amazon">
                    Amazon
                  </a></li>
                  <li><a href="#" class="brand-button" id="AliExpress">
                    <img src="images/aliexpress-logo.png" alt="AliExpress">
                    AliExpress
                  </a></li>
                  <li><a href="#" class="brand-button" id="eBay">
                    <img src="images/ebay-logo.png" alt="eBay">
                    eBay
                  </a></li>
                  <li><a href="#" class="brand-button" id="G2A">
                    <img src="images/g2a-logo.png" alt="G2A">
                    G2A
                  </a></li>
                  <li><a href="#" class="brand-button" id="Groupon">
                    <img src="images/groupon-logo.png" alt="Groupon">
                    Groupon
                  </a></li>
                  <li><a href="#" class="brand-button" id="iTunes">
                    <img src="images/itunes-logo.png" alt="iTunes">
                    iTunes
                  </a></li>
                  <li><a href="#" class="brand-button" id="Steam">
                    <img src="images/steam-logo.png" alt="Steam">
                    Steam
                  </a></li>
                  <li><a href="#" class="brand-button" id="Wish">
                    <img src="images/wish-logo.png" alt="Wish">
                    Wish
                  </a></li>
                  <li><a href="#" class="brand-button" id="Walmart">
                    <img src="images/walmart-logo.png" alt="Walmart">
                    Walmart
                  </a></li>
                  <li><a href="#" class="brand-button" id="Wix">
                    <img src="images/wix-logo.png" alt="Wix">
                    Wix
                  </a></li>
                  <li><a href="#" class="brand-button" id="Yahoo">
                    <img src="images/yahoo-logo.png" alt="Yahoo">
                    Yahoo
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Gaming
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Battle.net">
                    <img src="images/battlenet-logo.png" alt="Battle.net">
                    Battle.net
                  </a></li>
                  <li><a href="#" class="brand-button" id="Epic Games">
                    <img src="images/epicgames-logo.png" alt="Epic Games">
                    Epic Games
                  </a></li>
                  <li><a href="#" class="brand-button" id="Origin">
                    <img src="images/origin-logo.png" alt="Origin">
                    Origin
                  </a></li>
                  <li><a href="#" class="brand-button" id="PlayStation">
                    <img src="images/playstation-logo.png" alt="PlayStation">
                    PlayStation
                  </a></li>
                  <li><a href="#" class="brand-button" id="Riot Games">
                    <img src="images/riotgames-logo.png" alt="Riot Games">
                    Riot Games
                  </a></li>
                  <li><a href="#" class="brand-button" id="Steam">
                    <img src="images/steam-logo.png" alt="Steam">
                    Steam
                  </a></li>
                  <li><a href="#" class="brand-button" id="Twitch">
                    <img src="images/twitch-logo.png" alt="Twitch">
                    Twitch
                  </a></li>
                  <li><a href="#" class="brand-button" id="Xbox">
                    <img src="images/xbox-logo.png" alt="Xbox">
                    Xbox
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Services
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Uber">
                    <img src="images/uber-logo.png" alt="Uber">
                    Uber
                  </a></li>
                  <li><a href="#" class="brand-button" id="Airbnb">
                    <img src="images/airbnb-logo.png" alt="Airbnb">
                    Airbnb
                  </a></li>
                  <li><a href="#" class="brand-button" id="Lyft">
                    <img src="images/lyft-logo.png" alt="Lyft">
                    Lyft
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Online Storage
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Dropbox">
                    <img src="images/dropbox-logo.png" alt="Dropbox">
                    Dropbox
                  </a></li>
                  <li><a href="#" class="brand-button" id="OneDrive">
                    <img src="images/onedrive-logo.png" alt="OneDrive">
                    OneDrive
                  </a></li>
                  <li><a href="#" class="brand-button" id="Google Drive">
                    <img src="images/googledrive-logo.png" alt="Google Drive">
                    Google Drive
                  </a></li>
                  <li><a href="#" class="brand-button" id="Box">
                    <img src="images/box-logo.png" alt="Box">
                    Box
                  </a></li>
                  <li><a href="#" class="brand-button" id="Jotta">
                    <img src="images/jotta-logo.png" alt="Jotta">
                    Jotta
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Streaming
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="Facebook">
                    <img src="images/facebook-logo.png" alt="Facebook">
                    Facebook
                  </a></li>
                  <li><a href="#" class="brand-button" id="YouTube">
                    <img src="images/youtube-logo.png" alt="YouTube">
                    YouTube
                  </a></li>
                  <li><a href="#" class="brand-button" id="Netflix">
                    <img src="images/netflix-logo.png" alt="Netflix">
                    Netflix
                  </a></li>
                  <li><a href="#" class="brand-button" id="Spotify">
                    <img src="images/spotify-logo.png" alt="Spotify">
                    Spotify
                  </a></li>
                  <li><a href="#" class="brand-button" id="Tidal">
                    <img src="images/tidal-logo.png" alt="Tidal">
                    Tidal
                  </a></li>
                  <li><a href="#" class="brand-button" id="SoundCloud">
                    <img src="images/soundcloud-logo.png" alt="SoundCloud">
                    SoundCloud
                  </a></li>
                  <li><a href="#" class="brand-button" id="Hulu">
                    <img src="images/hulu-logo.png" alt="Hulu">
                    Hulu
                  </a></li>
                  <li><a href="#" class="brand-button" id="Amazon Prime">
                    <img src="images/amazon-logo.png" alt="Amazon Prime">
                    Amazon Prime
                  </a></li>
                  <li><a href="#" class="brand-button" id="HBO">
                    <img src="images/hbo-logo.png" alt="HBO">
                    HBO
                  </a></li>
                  <li><a href="#" class="brand-button" id="Disney+">
                    <img src="images/disney-logo.png" alt="Disney+">
                    Disney+
                  </a></li>
                  <li><a href="#" class="brand-button" id="Apple TV+">
                    <img src="images/appletv-logo.png" alt="Apple TV+">
                    Apple TV+
                  </a></li>
                  <li><a href="#" class="brand-button" id="CBS All Access">
                    <img src="images/cbs-logo.png" alt="CBS All Access">
                    CBS All Access
                  </a></li>
                  <li><a href="#" class="brand-button" id="Crunchyroll">
                    <img src="images/crunchyroll-logo.png" alt="Crunchyroll">
                    Crunchyroll
                  </a></li>
                  <li><a href="#" class="brand-button" id="HBO Max">
                    <img src="images/hbo-logo.png" alt="HBO Max">
                    HBO Max
                  </a></li>
                </ul>
              </li>
              <li>
                <a href="#" class="brand-button">
                  Anti-Virus
                </a>
                <ul class="submenu">
                  <li><a href="#" class="brand-button" id="McAfee">
                    <img src="images/mcafee-logo.png" alt="McAfee">
                    McAfee
                  </a></li>
                  <li><a href="#" class="brand-button" id="Kaspersky">
                    <img src="images/kaspersky-logo.png" alt="Kaspersky">
                    Kaspersky
                  </a></li>
                  <li><a href="#" class="brand-button" id="Avast">
                    <img src="images/avast-logo.png" alt="Avast">
                    Avast
                  </a></li>
                  <li><a href="#" class="brand-button" id="Avira">
                    <img src="images/avira-logo.png" alt="Avira">
                    Avira
                  </a></li>
                  <li><a href="#" class="brand-button" id="Bitdefender">
                    <img src="images/bitdefender-logo.png" alt="Bitdefender">
                    Bitdefender
                  </a></li>
                  <li><a href="#" class="brand-button" id="Norton">
                    <img src="images/norton-logo.png" alt="Norton">
                    Norton
                  </a></li>
                  <li><a href="#" class="brand-button" id="ESET">
                    <img src="images/eset-logo.png" alt="ESET">
                    ESET
                  </a></li>
                  <li><a href="#" class="brand-button" id="Panda">
                    <img src="images/panda-logo.png" alt="Panda">
                    Panda
                  </a></li>
                  <li><a href="#" class="brand-button" id="Comodo">
                    <img src="images/comodo-logo.png" alt="Comodo">
                    Comodo
                  </a></li>
                  <li><a href="#" class="brand-button" id="Sophos">
                    <img src="images/sophos-logo.png" alt="Sophos">
                    Sophos
                  </a></li>
                  <li><a href="#" class="brand-button" id="Trend Micro">
                    <img src="images/trendmicro-logo.png" alt="Trend Micro">
                    Trend Micro
                  </a></li>
                  <li><a href="#" class="brand-button" id="F-Secure">
                    <img src="images/fsecure-logo.png" alt="F-Secure">
                    F-Secure
                  </a></li>
                  <li><a href="#" class="brand-button" id="VIPRE">
                    <img src="images/vipre-logo.png" alt="VIPRE">
                    VIPRE
                  </a></li>
                  <li><a href="#" class="brand-button" id="Webroot">
                    <img src="images/webroot-logo.png" alt="Webroot">
                    Webroot
                  </a></li>
                  <li><a href="#" class="brand-button" id="ZoneAlarm">
                    <img src="images/zonealarm-logo.png" alt="ZoneAlarm">
                    ZoneAlarm
                  </a></li>
                  <li><a href="#" class="brand-button" id="Malwarebytes">
                    <img src="images/malwarebytes-logo.png" alt="Malwarebytes">
                    Malwarebytes
                  </a></li>
                  <li><a href="#" class="brand-button" id="Emsisoft">
                    <img src="images/antivirus-logo.png" alt="Emsisoft">
                    Emsisoft
                  </a></li>
                  <li><a href="#" class="brand-button" id="AVG">
                    <img src="images/avg-logo.png" alt="AVG">
                    AVG
                  </a></li>
                  <li><a href="#" class="brand-button" id="Dr.Web">
                    <img src="images/drweb-logo.png" alt="Dr.Web">
                    Dr.Web
                  </a></li>
                  <li><a href="#" class="brand-button" id="G Data">
                    <img src="images/gdata-logo.png" alt="G Data">
                    G Data
                  </a></li>
                  <li><a href="#" class="brand-button" id="K7">
                    <img src="images/k7-logo.png" alt="K7">
                    K7
                  </a></li>
                  <li><a href="#" class="brand-button" id="Quick Heal">
                    <img src="images/quickheal-logo.png" alt="Quick Heal">
                    Quick Heal
                  </a></li>
                </ul>
              </li>
            </ul>
          </nav>

          <?php
// Establish a database connection
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

// Prepare the SELECT statement
$stmt = mysqli_prepare($conn, "SELECT `id`, `logo_path`, `name`, `info` FROM `brands`");

// Execute the statement
mysqli_stmt_execute($stmt);

// Bind the result variables
mysqli_stmt_bind_result($stmt, $id, $logoPath, $name, $info);

// Loop through the results and generate the HTML
while (mysqli_stmt_fetch($stmt)) {
    echo '<a href="#" class="brand-button" id="' . $name . '">';
    echo '<img src="' . $logoPath . '" alt="' . $name . '">';
    echo $name;
    echo '</a>';
}

// Close the statement and the connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>


          <a href="#" class="brand-button" id="Google">
            <img src="images/google-logo.png" alt="Google">
            Google
          </a>
          <a href="#" class="brand-button" id="Microsoft">
            <img src="images/microsoft-logo.png" alt="Microsoft">
            Microsoft
          </a>
          <a href="#" class="brand-button" id="PIN">
            <img src="images/office-logo.png" alt="Office License">
            Office License
          </a>
          <a href="#" class="brand-button" id="Apple">
            <img src="images/apple-logo.png" alt="Apple">
            Apple
          </a>
          <a href="#" class="brand-button" id="McAfee">
            <img src="images/mcafee-logo.png" alt="McAfee">
            McAfee
          </a>
          <a href="#" class="brand-button" id="Jotta">
            <img src="images/jotta-logo.png" alt="Jotta">
            Jotta
          </a>
          <a href="#" class="brand-button" id="Facebook">
            <img src="images/facebook-logo.png" alt="Facebook">
            Facebook
          </a>
          <a href="#" class="brand-button" id="eBay">
            <img src="images/ebay-logo.png" alt="eBay">
            eBay 
          </a>
          <a href="#" class="brand-button" id="WordPress">
            <img src="images/wordpress-logo.png" alt="WordPress">
            WordPress
          </a>
          <a href="#" class="brand-button" id="Instagram">
            <img src="images/instagram-logo.png" alt="Instagram">
            Instagram
          </a>
          <a href="#" class="brand-button" id="Spotify">
            <img src="images/spotify-logo.png" alt="Spotify">
            Spotify
          </a>
          <a href="#" class="brand-button" id="Uber">
            <img src="images/uber-logo.png" alt="Uber">
            Uber
          </a>
          <a href="#" class="brand-button" id="Airbnb">
            <img src="images/airbnb-logo.png" alt="Airbnb">
            Airbnb
          </a>
          <a href="#" class="brand-button" id="Lyft">
            <img src="images/lyft-logo.png" alt="Lyft">
            Lyft
          </a>
          <a href="#" class="brand-button" id="TelenorID">
            <img src="images/telenor-logo.png" alt="TelenorID">
            TelenorID
          </a>
          <a href="#" class="brand-button" id="Online">
            <img src="images/telenor-logo.png" alt="Online">
            Online
          </a>
          <a href="#" class="brand-button" id="PIN">
            <img src="images/pin-logo.png" alt="Screenlock Code">
            Screenlock Code
          </a>
          <a href="#" class="brand-button" id="PIN">
            <img src="images/sim-logo.png" alt="SIM PIN-Code">
            SIM PIN-Code
          </a>
      </div>
    </div>
    <script src="script.js"></script>
  </body>
</html>

