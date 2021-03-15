<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<h1>Hello, world!</h1>


<div class="container-md p-3 my-3 bg-dark text-white">
<h1>My First Bootstrap Page</h1>
<p>This is some text.</p>
</div>


<div class="row">
    <div class="col container-md p-3 my-3 bg-dark text-white">.col</div>
    <div class="col container-md p-3 my-3 bg-dark text-white">.col</div>
    <div class="col container-md p-3 my-3 bg-dark text-white">.col</div>
</div>


<div class="row">
    <div class="col-sm-4">.col-sm-4</div>
    <div></div>
    <div class="col-sm-8">.col-sm-8</div>
</div>

</body>
</html>
