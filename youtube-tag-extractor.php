<?php
$api_key = 'add_your_youtube_video_api';
  
function getYTTags($api_url = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $arr_result = json_decode($response);
    if (isset($arr_result->items) && isset($arr_result->items[0]->snippet->tags)) {
        return $arr_result->items[0]->snippet->tags;
    } elseif (isset($arr_result->error)) {
        die("No video tags found.");
    }
}
  
function extractVideoID($url){
    $regExp = "/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/";
    preg_match($regExp, $url, $video);
    return $video[7];
}
  
$arr_tags = array();
if (array_key_exists('ytvideo', $_GET)) {
    extract($_GET);
    $video_id = extractVideoID($ytvideo);
    $api_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$video_id&type=video&key=$api_key";
    $arr_tags = getYTTags($api_url);
}
?>
<html>
  <head>
    <title>YouTube Video Tag Extractor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <br>
<div class="container">
<form method="get" class="form-control">
  <h1 class="text-center">YouTube Video Tag Extractor</h1>
    
    <br><p>
        <input class="form-control" type="text" name="ytvideo" placeholder="Enter YouTube Video URL" value="<?php if (array_key_exists('ytvideo', $_GET)) echo $_GET['ytvideo']; ?>" required />
    </p>
    <p>
        <input type="submit" class="btn btn-primary col-12" name="submit" value="Submit">
    </p>
</form>
</div>
</body>
</html>
<?php
if (!empty($arr_tags)) {
    echo '<div class="container">';
    echo "<ul>";
    foreach ($arr_tags as $tag) {
        echo "<li>$tag</li>";
    }
    echo "</ul>";
    echo '</div>';
}