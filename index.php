<?php
$userdata = json_decode(file_get_contents('/Library/Server/Web/Data/Sites/Default/about/data.json'), true);
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="/about/Style.css">
<script src="https://unpkg.com/typed.js@2.0.132/dist/typed.umd.js"></script>
<script src="/about/index.js"></script>

<head>
    <title>About Me (<?php echo  $userdata['name']; ?>)</title>
</head>

<body>
    <div class="profile-header profile-same">
        <div class="profile-avatar">
            <img src="<?php echo  $userdata['image']; ?>">
        </div>
        <h1><?php echo  $userdata['name']; ?></h1>
        <h2> <?php echo AutoTyped($userdata["Summary"], $userdata["AutoType"]); ?> </h2>
        <h3> <?php echo  $userdata['fraze'];  ?> </h3>
        <div class="profile-links">
            <?php echo CreateLinks($userdata['links']); ?>
        </div>
    </div>
    <div class="profile-content">
        <?php echo Create_UserContent($userdata); ?>
    </div>





</body>
<footer>
    <div class="container2">
        <p>&copy; <span id="currentYear"></span> Thomasdye.net. All rights reserved.</p>
    </div>
    <script>
        var currentYear = new Date().getFullYear();
        document.getElementById("currentYear").innerHTML = currentYear;
    </script>
</footer>

</html>


<?php

// still to do
// footer 
// add more data to the json file
// UPDATE brits json 


// creater links html 


function AutoTyped($fallback, $autotypedata = null)
{
    $randomspan = "Summerytyped" . rand(0, 1000000);
    if ($autotypedata == null) {
        return $fallback;
    }
    $output = $autotypedata["Leading"];
    $output .= ' <span id="' . $randomspan . '"></span>';
    //     <script>
    //     var typed = new Typed('#Summerytyped', {
    //       strings: ['<i>First</i> sentence.', '&amp; a second sentence.'],
    //       typeSpeed: 50,
    //     });
    //   </script>
    $output .= "<script>";
    $output .= "new Typed('#" . $randomspan . "', {";
    $output .= "strings: [";
    foreach ($autotypedata["endings"] as $string) {
        $output .= "'" . $string . "',";
    }
    $output .= "],";
    $output .= "typeSpeed: " . $autotypedata["speed"] . ",";
    $output .= "});";
    $output .= "</script>";
    return $output;
}



function Create_UserContent($userdata)
{
    $output = '';
    // loop over Content 
    foreach ($userdata["Content"] as $content) {
        // if show is fualse skip
        if (isset($content["show"]) && $content["show"] == false) {
            continue;
        }
        // if the type is Title_text
        if ($content["type"] == "Title_text") {
            $output .= Create_Title_text($content);
        }
        // if the type is tilelist
        if ($content["type"] == "Title_list") {
            $output .= Create_Titllist($content);
        }
        // if the type is timeline
        if ($content["type"] == "timeline") {
            $output .= Timeline($content["data"]);
        }
        // if the type is Title_Images
        if ($content["type"] == "Title_Images") {
            $output .= Create_Title_Images($content);
        }
    }
    return $output;
}

function CreateLinks($linkdata)
{
    // sort the data in order of name
    usort($linkdata, function ($a, $b) {
        return $a['name'] <=> $b['name'];
    });
    $output = '';

    foreach ($linkdata as $link) {
        $output .= '<a href="' . $link["url"] . '">';
        $output .= '<i class="' . $link["icon"] . '"></i>';
        $output .= '<span>' . $link["name"] . '</span>';
        $output .= '</a>';
    }

    return $output;
}




function Create_Title_text($data)
{

    $output = '<div id="about-us" class="container">';
    $output .= '<div class="title">';
    $output .= '<h1>';
    $output .= 'About Me';
    $output .= '</h1>';
    $output .= '<div class="image">';
    $output .= '<img src="' . $data["image"] . '">';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="text"> ' . AutoTyped($data["text"], isset($data["speed"]) ? array("Leading" => "", "endings" => array($data["text"]), "speed" => $data["speed"]) : null) . '</div>';
    $output .= '</div>';
    return $output;
}

function Create_Titllist($data)
{
    $output = ' <div class="containerlist">';
    $output .= '<h1 class="title" >' . $data["title"] . '</h1>';
    $output .= '<ul>';
    foreach ($data["list"] as $item) {
        $output .= '<li>';
        $output .= '<span class= "objecttitle">' . $item["title"] . ' </span>';
        $output .= '<span class= "text"> - ' . $item["description"] . '</span>';
        $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';
    return $output;
}

function Timeline($data)
{
    $output = '<ul class="timeline">';

    foreach ($data as $index => $item) {
        // if show is fualse skip
        if (isset($item["show"]) && $item["show"] == false) {
            continue;
        }
        $direction = "r";
        // calculate the direction, based on the index
        if ($index % 2 == 0) {
            $direction = "l";
        }
        $output .= '<li>';
        $output .= '<div class="direction-' .  $direction . '">';
        $output .= '<div class="flag-wrapper">';
        $output .= '<span class="flag">' . $item["title"] . '</span>';
        $date = $item["date"]["start"] . " - " . $item["date"]["end"];
        $output .= '<span class="time-wrapper"><span class="time">' .    $date . '</span></span>';
        $output .= '</div>';
        $output .= '<div class="desc">' . $item["description"] . '</div>';
        $output .= '</div>';
        $output .= '</li>';
    }
    $output .= '</ul>';
    return $output;
}

function Create_Title_Images($data)
{
    $output = '<div class="containerimage">';
    $output .= '<h1 class="title" >' . $data["title"] . '</h1>';
    $output .= '<div class="images-container">';
    foreach ($data["images"] as $image) {
        $output .= '<div class="text_image">';
        $output .= '<div class="image">';
        $output .= '<img src="' . $image["url"] . '">';
        $output .= '</div>';
        $output .= '<div class="text">' . $image["text"] . '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}



// <div id="container">
// <?php
// // Load the JSON data from a file


// // Display the person's name and image
// echo '<h1>' . $userdata['name'] . '</h1>';
// echo '<img src="' . $userdata['image'] . '" alt="">';

// // Display the text about the person
// echo '<p>' . $userdata['about'] . '</p>';

// // Display the person's jobs/skills
// foreach ($userdata['jobs'] as $job) {
//     echo '<div class="job">';
//     echo '<h3>' . $job['title'] . '</h3>';
//     echo '<p>' . $job['description'] . '</p>';

//     if (isset($job['image'])) {
//         echo '<img src="' . $job['image'] . '" alt="' . $job['title'] . '">';
//     }

//     echo '</div>';
// }
// 
// </div>