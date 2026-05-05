<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="font-sans antialiased">
    <h2>Image Gallery Slider</h2>

    @php
    $p = new \Atomjoy\Parsedown\ParsedownMedia();

    // Gallery image slider
    echo $p->text("&&&box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n&&&");

    // Gallery image list
    // echo $p->text("%%%box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n%%%");
    @endphp

    <h2>Audio Video Embed</h2>

    @php
    $p = new \Atomjoy\Parsedown\ParsedownMedia();

    echo $p->text("{Video description goes here.}(embed)(https://www.youtube.com/embed/NGsjwNsXE0w?si=eI0hyNw8NE_X61v4)");
    echo $p->text("{Video description goes here.}(video)(https://afe019d0-9895-400c-9385-5c25c4775c8e.mdnplay.dev/shared-assets/videos/flower.webm)");
    echo $p->text("{Audio description goes here.}(audio)(https://361fc8d0-210a-4e2b-9cff-6f13be3457ad.mdnplay.dev/shared-assets/audio/t-rex-roar.mp3)");
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <script>
    /* Required */
    onload = (event) => {
        new Splide('.splide').mount();
    }
    </script>

    <style>
        .media-wrapper {
            margin: 32px auto;
            max-width: 50%;
        }

        .splide {
            background: #fafafa;
            width: 100%;
            height: 300px;
            /* aspect-ratio: 16/9; */
        }

        .splide__image {
            object-fit: cover;
            width: 100%;
            height: 300px;
            /* aspect-ratio: 16/9; */
        }
    </style>
</body>

</html>
