<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World - PHP Template</title>
</head>
<body style="padding: 2rem; font-family: sans-serif;">
    <h1>Hello World from PHP Template!</h1>
    <p><strong>Your IP Address:</strong> <?php echo $view->escape($client_ip) ?></p>
    <p><strong>Current Time:</strong> <?php echo $current_time->format('F j, Y g:i:s A') ?></p>
    <hr>
    <p><a href="<?= $path->generate('hello_twig') ?>">View Twig Version</a></p>
</body>
</html>
