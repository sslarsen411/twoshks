<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>testies</title>
    @vite('resources/css/app.css')
</head>
<body>
{{ 'hello' }}
<div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
    <div class="col-span-10">
        <h3>
            TITLE
        </h3>
        <p>
            CONTENT
        </p>
    </div>
    <div class="col-span-2 ">
        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.webp')}}" class="guru-icon"
             alt="Review Guru icon">
    </div>
    <div class="col-span-2 ">
        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.webp')}}" class="guru-icon"
             alt="Review Guru icon">
    </div>
    <div class="col-span-10">
        <h3>
            TITLE
        </h3>
        <p>
            CONTENT
        </p>
    </div>
</div>
</body>
</html>
