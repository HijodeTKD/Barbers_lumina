<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="preload" as="image" href="/build/img/1.jpg" >
</head>
<body>
    <div class="contenedor-app">
        <img class="imagen" src="/build/img/1.jpg">
        <div class="app"><?php echo $contenido; ?></div>
    </div>

    <script src='/build/js/ui.js'></script>
    <?php echo $script ?? ''; ?>
</body>
</html>