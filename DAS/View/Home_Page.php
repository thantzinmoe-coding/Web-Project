<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <link rel="stylesheet" href="./CSS/Home_page.css">
</head>

<body>
    <!-- HEADER -->
    <header>
        <nav>
            <div class="logo">DAS</div>
            <ul>
                <li><a href="Home_page.php">Home</a></li>
                <li><a href="./Find_Doctor.php">Find Doctor</a></li>
                <li><a href="./Find_Hospital.php">Find Hospital</a></li>
                <li><a href="./Sign_In.php">Sign In</a></li>
            </ul>
        </nav>
    </header>
    <!-- carousel -->
    <div class="carousel">
        <!-- list item -->
        <div class="list">
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (5).jpg">
                <div class="content">
                    <div class="hospital">Hospital Name</div>
                    <div class="name">CITY</div>
                    <div class="topic">RATING</div>
                    <div class="des">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus, none
                    </div>
                    <div class="buttons">
                        <button>SEE MORE</button>
                        <button>BOOK</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (4).jpg">
                <div class="content">
                    <div class="hospital">Hospital Name</div>
                    <div class="name">CITY</div>
                    <div class="topic">RATING</div>
                    <div class="des">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nobis, ullam.
                    </div>
                    <div class="buttons">
                        <button>SEE MORE</button>
                        <button>BOOK</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (3).jpg">
                <div class="content">
                    <div class="hospital">Hospital Name</div>
                    <div class="name">CITY</div>
                    <div class="topic">RATING</div>
                    <div class="des">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse, ipsa!
                    </div>
                    <div class="buttons">
                        <button>SEE MORE</button>
                        <button>BOOK</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (2).jpg">
                <div class="content">
                    <div class="hospital">Hospital Name</div>
                    <div class="name">CITY</div>
                    <div class="topic">RATING</div>
                    <div class="des">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Labore, placeat.
                    </div>
                    <div class="buttons">
                        <button>SEE MORE</button>
                        <button>BOOK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- list thumnail -->
        <div class="thumbnail">
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (5).jpg">
                <div class="content">
                    <div class="title">
                        Hospital Name
                    </div>
                    <div class="description">
                        CITY
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (4).jpg">
                <div class="content">
                    <div class="title">
                        Hospital Name
                    </div>
                    <div class="description">
                        CITY
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (3).jpg">
                <div class="content">
                    <div class="title">
                        Hospital Name
                    </div>
                    <div class="description">
                        CITY
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../Image/wallpaperflare.com_wallpaper (2).jpg">
                <div class="content">
                    <div class="title">
                        Hospital Name
                    </div>
                    <div class="description">
                        CITY
                    </div>
                </div>
            </div>
        </div>
        <!-- next prev -->
        <div class="arrows">
            <button id="prev">
                << </button>
                    <button id="next">></button>
        </div>
        <!-- time running -->
        <div class="time"></div>
    </div>
</body>
<script src="./JS/Home_Page.js"></script>

</html>