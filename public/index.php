<?php

if (!empty($_GET['name'])) {
    $response = file_get_contents("https://api.agify.io?name={$_GET['name']}");
    $data = json_decode($response, true);
    $age = $data['age'];
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Example</title>
</head>
<body>
<?php if (isset($age)) :?>
Age: <?=$age?>
<? endif; ?>
<form action="">
    <label for="name">Name</label>
    <input type="text" name="name" id="name">
    <button>Guess age</button>
</form>
</body>
</html>
