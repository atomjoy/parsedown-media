# Parsedown Media

Create audio, video, embed video and image gallery with slider from Markdown file.

## Install

```sh
composer require atomjoy/parsedown-media
```

## How to Media Files

```php
<?php

$p = new \Atomjoy\Parsedown\ParsedownMedia();

echo $p->text("{Description or empty}(embed)(https://www.youtube.com/embed/y5g3UMaODxo?si=evrMVC4n9DbZ3OQD)");
echo $p->text("{Description or empty}(video)(https://afe019d0-9895-400c-9385-5c25c4775c8e.mdnplay.dev/shared-assets/videos/flower.webm)");
echo $p->text("{Description or empty}(audio)(https://361fc8d0-210a-4e2b-9cff-6f13be3457ad.mdnplay.dev/shared-assets/audio/t-rex-roar.mp3)");
```

## Created Media html

```html
<!-- Embed -->
<div class="media-wrapper">
    <iframe
        width="100%"
        src="https://www.youtube.com/embed/NGsjwNsXE0w?si=eI0hyNw8NE_X61v4"
        style="aspect-ratio: 16/9;"
        frameborder="0"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen=""
    ></iframe>
    <p class="media-description">Video description goes here.</p>
</div>

<!-- Video -->
<div class="media-wrapper">
    <video controls="" width="100%" style="aspect-ratio: 16/9;">
        <source
            src="https://afe019d0-9895-400c-9385-5c25c4775c8e.mdnplay.dev/shared-assets/videos/flower.webm"
        />
    </video>
    <p class="media-description">Video description goes here.</p>
</div>
```

## How to ParsedownGallery

```php
<?php

$p = new \Atomjoy\Parsedown\ParsedownMedia();

// Default class .gallery-content
echo $p->text("%%%\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n%%%");

// Custom class .gallery-box
echo $p->text("%%%box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n%%%");
```

### Created ParsedownGallery html

```html
<div class="gallery-wrapper">
    <ul class="gallery-content">
        <li>
            <img
                src="https://img.icons8.com/bubbles/100/google-logo.jpg"
                data-image="1"
                class="gallery-image gallery-image-1"
                alt="Image"
            />
        </li>
        <li>
            <img
                src="https://img.icons8.com/bubbles/100/google-logo.jpg"
                data-image="2"
                class="gallery-image gallery-image-2"
                alt="Image"
            />
        </li>
        <li>
            <img
                src="https://img.icons8.com/bubbles/100/google-logo.jpg"
                data-image="3"
                class="gallery-image gallery-image-3"
                alt="Image"
            />
        </li>
    </ul>
</div>
```

## How to ParsedownGallerySplide

```php
<?php

$p = new \Atomjoy\Parsedown\ParsedownMedia();

echo $p->text("&&&\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n&&&");

echo $p->text("&&&box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n&&&");
```

### Created ParsedownGallerySplide html

```html
<div class="splide">
    <div class="splide__track">
        <ul class="splide__list">
            <li class="splide__slide">
                <img
                    src="https://img.icons8.com/bubbles/200/google-logo.jpg"
                    data-image="1"
                    class="splide__image splide__image__1"
                    alt="Image"
                />
            </li>
            <li class="splide__slide">
                <img
                    src="https://img.icons8.com/bubbles/200/google-logo.jpg"
                    data-image="2"
                    class="splide__image splide__image__2"
                    alt="Image"
                />
            </li>
            <li class="splide__slide">
                <img
                    src="https://img.icons8.com/bubbles/200/google-logo.jpg"
                    data-image="3"
                    class="splide__image splide__image__3"
                    alt="Image"
                />
            </li>
            <li class="splide__slide">
                <img
                    src="https://img.icons8.com/bubbles/200/google-logo.jpg"
                    data-image="4"
                    class="splide__image splide__image__4"
                    alt="Image"
                />
            </li>
            <li class="splide__slide">
                <img
                    src="https://img.icons8.com/bubbles/200/google-logo.jpg"
                    data-image="5"
                    class="splide__image splide__image__5"
                    alt="Image"
                />
            </li>
        </ul>
    </div>
</div>
```

### Add Splide slider plugin and css

```html
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<link
    href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css"
    rel="stylesheet"
/>

<script>
    /* Required */
    document.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(() => {
            new Splide('.splide').mount();
        }, 1000);
    });
</script>

<style>
    .splide {
        background: #fafafa;
        width: 100%;
        height: 400px;
        /* aspect-ratio: 16/9; */
    }

    .splide__image {
        object-fit: cover;
        width: 100%;
        height: 400px;
        /* aspect-ratio: 16/9; */
    }
</style>
```

## Parse ToDo List

Add fontawesome cdn links in head first.

```md
### ToDo list fontawesome

- [x] Finish my changes
- [ ] Push my commits to GitHub
- [ ] Open a pull request
- [x] @mentions, #refs, [links](), **formatting**, and <del>tags</del> supported
- [x] list syntax required (any unordered or ordered list supported)
- [x] this is a complete item
- [ ] this is an incomplete item
```

### ToDo list html

```html
<ul class="todo-list">
    <li class="todo-item list-none">
        <i class="todo-icon fas fa-check-square todo-icon-checked"></i>
        <span class="todo-text">Finish my changes</span>
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon far fa-square"></i>
        <span class="todo-text">Push my commits to GitHub</span>
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon far fa-square"></i>
        <span class="todo-text">Open a pull request</span>
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon fas fa-check-square todo-icon-checked"></i>
        <span class="todo-text"
            >@mentions, #refs, [links](), **formatting**, and
            &lt;del&gt;tags&lt;/del&gt; supported</span
        >
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon fas fa-check-square todo-icon-checked"></i>
        <span class="todo-text"
            >list syntax required (any unordered or ordered list
            supported)</span
        >
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon fas fa-check-square todo-icon-checked"></i>
        <span class="todo-text">this is a complete item</span>
    </li>
    <li class="todo-item list-none">
        <i class="todo-icon far fa-square"></i>
        <span class="todo-text">this is an incomplete item</span>
    </li>
</ul>
```

## Secure input (optional)

```php
$p = new ParsedownMedia();

$p->setSafeMode(true);
```
