<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $article->title }}</title>
    <link rel="stylesheet" href="{{ asset('fonts/Montserrat/font.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/OpenSans/font.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">

    <script src="{{ asset('scripts/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('scripts/index.js') }}" defer></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__body">
                <picture>
                    <source srcset="{{ asset('js/images/logo.svg') }}" media="(min-width: 320px)">
                    <img class="logo" src="{{ asset('js/images/logo.svg') }}" alt="">
                </picture>
                <div class="authorization">
                    <button class="authorization__btn">
                        <span class="authorization__btn-text">Завантажити</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <main class="main">
        <article class="article">
            <div class="container">
                <div class="article__body">
                    <div class="article__box">
                        <h1 class="article__title">{{ $article->title }}</h1>
                        <div class="article__desc">
                            <span class="article__desc-text article__desc-author">Майа Ярова</span>
                            <span class="article__desc-text article__desc-views">{{ $article->views }}</span>
                            <span class="article__desc-text article__desc-date">{{ $article->created_at }}</span>
                        </div>
                    </div>
                    <div class="article__info">
                        <div class="swiper-container article-container">
                            <div class="swiper-wrapper article-wrapper">
                                <div class="swiper-slide article-slide">
                                    <div class="star">
                                        <img class="star__img" src="./images/icons/star.svg" alt="">
                                    </div>
                                    <div class="like">
                                        <img class="like__img" src="./images/icons/like.svg" alt="">
                                        <span class="like__amount">25</span>
                                    </div>
                                    <img class="article-slide__img" src="./images/page.png" alt="">
                                </div>
                                <div class="swiper-slide article-slide">
                                    <div class="star">
                                        <img class="star__img" src="./images/icons/star.svg" alt="">
                                    </div>
                                    <div class="like">
                                        <img class="like__img" src="./images/icons/like.svg" alt="">
                                        <span class="like__amount">25</span>
                                    </div>
                                    <img class="article-slide__img" src="./images/page.png" alt="">
                                </div>
                                <div class="swiper-slide article-slide">
                                    <div class="star">
                                        <img class="star__img" src="./images/icons/star.svg" alt="">
                                    </div>
                                    <div class="like">
                                        <img class="like__img" src="./images/icons/like.svg" alt="">
                                        <span class="like__amount">25</span>
                                    </div>
                                    <img class="article-slide__img" src="./images/page.png" alt="">
                                </div>
                                <div class="swiper-slide article-slide">
                                    <div class="star">
                                        <img class="star__img" src="./images/icons/star.svg" alt="">
                                    </div>
                                    <div class="like">
                                        <img class="like__img" src="./images/icons/like.svg" alt="">
                                        <span class="like__amount">25</span>
                                    </div>
                                    <img class="article-slide__img" src="./images/page.png" alt="">
                                </div>
                            </div>
                            <div class="swiper-pagination article-pagination"></div>
                        </div>

                        @foreach( $content as $section )
                            <div class="article__content">
                                <div class="wrapper">
                                    <h4 class="article__content-subtitle">{{ $section->title }}</h4>
                                    <p class="article__content-text">{{ $section->content }}</p>
                                </div>
                            </div>
                        @endforeach

                        <button class="article__btn">
                            <span class="article__btn-text">Читати ще</span>
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <section class="community">
            <div class="container">
                <div class="community__body">
                    <h2 class="community__title">Ком'юніті лікарів України</h2>
                    <a class="community__link" href="#">
                        <span class="community__link-text">Група Facebook</span>
                    </a>
                </div>
            </div>
        </section>

    </main>
</body>
</html>
