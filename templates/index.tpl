<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agency Creative</title>
    <link rel="stylesheet" href="/src/style.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis:500,600|Open+Sans:400,600" rel="stylesheet">


    <script src="//code.jquery.com/jquery-2.1.4.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/jquery.form.min.js"></script>

</head>
<body>

<header class="header">
    <div class="header__wrapper wrapper">
        <div class="logo"><a class="link" href="/"><img src="/src/img/logo.svg" alt="logo"></a></div>
        <nav class="header__nav nav">
            <input type="checkbox" class="nav__check">
            <span class="nav__line"></span>
            <span class="nav__line"></span>
            <span class="nav__line"></span>
            <ul class="header__menu menu">
                <li class="menu__item"><a href="/" class="link menu__link">Головна</a></li>
                <li class="menu__item"><a href="#services" class="link menu__link">Сервіси</a></li>
                <li class="menu__item"><a href="#blog" class="link menu__link">Блог</a></li>

                {if $auth}
                    <li class="menu__item"><a href="/index.php?method=/blog/new" class="link menu__link">Новина</a></li>
                    <li class="menu__item"><a href="/index.php?method=/account/logout" class="link menu__link">Вихід</a>
                    </li>
                {else}
                    <li class="menu__item"><a href="/index.php?method=/account/join" class="link menu__link">Реєстрація</a></li>
                    <li class="menu__item"><a href="/index.php?method=/account/login" class="link menu__link">Вхід</a>
                    </li>
                {/if}
            </ul>
        </nav>
    </div>
</header>

<section class="main-slider">
    <div class="main-slider__wrapper wrapper">
        <div class="main-slider__title title">
            <h1 class="main-slider__heading heading">СММ</h1>
            <h2 class="main-slider__subheading subheading">Агенство Renko</h2>
            <p class="main-slider__text text">Ми надаємо послуги з налаштування та ведення таргетированї реклами в
                соціальних мережах
                Співпраця з нами забезпечить ефективне просування Вашого бізнесу і посприяє залученню нових клієнтів
            </p>
        </div>
    </div>
    <a href="#services" class="main-slider__scroll scroll"></a>
</section>

<section class="services" id="services">
    <div class="services__wrapper wrapper">
        <div class="services__title title">
            <h3 class="services__heading heading">Послуги</h3>
            <h4 class="services__subheading subheading">all expert digital services</h4>
            <p class="services__text text">Налаштуванням і веденням займаються професіонали, які закінчили спеціальні
                курси і мають великий досвід роботи в цій сфері.
                Розробляємо ефективні і швидкі стратегії, для економії Вашого бюджету і підвищення конверсій.
            </p>
            <a href="#" class="services__btn btn">всі послуги</a>
        </div>
        <div class="services__grid grid">
            <div class="grid__item grid__item-top">
                <div class="grid__item_inner">
                    <img src="src/img/services/development.png" alt="icon-development" class="service_icon">
                    <h5 class="grid__description description">Створення таргетованої реклами</h5>
                </div>
            </div>
            <div class="grid__item grid__item-top grid__item-last">
                <div class="grid__item_inner">
                    <img src="src/img/services/web-security.png" alt="icon-security" class="service_icon">
                    <h5 class="grid__description description">Масштабування та оптимізація</h5>
                </div>
            </div>
            <div class="grid__item grid__item-bottom">
                <div class="grid__item_inner">
                    <img src="src/img/services/marketing.png" alt="icon-marketing" class="service_icon">
                    <h5 class="grid__description description">Створення воронки продажів</h5>
                </div>
            </div>
            <div class="grid__item grid__item-last grid__item-bottom">
                <div class="grid__item_inner">
                    <img src="src/img/services/optimization.png" alt="icon-conversion" class="service_icon">
                    <h5 class="grid__description description">Оптимізація конверсій</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog" id="blog">
    <div class="blog__wrapper wrapper">
        <div class="blog__title title">
            <h3 class="blog__heading heading">Наші роботи</h3>
            <p class="blog__text text">Таргетована реклама - перспективний напрямок просування різних товарів або послуг
                в соціальних мережах, таких як Facebook, Instagram і ін.
            </p>
        </div>
        <div class="blog__box">
            <div class="blog__post post">
                <img src="src/img/blog/1.jpeg" alt="image for post" class="post__image">
                <h5 class="post__description description">Доставка суші і бургерів.</h5>
                <address class="post__author">Автор: Кириченко Діма</address>
                <p class="post__text text">Новий ресторан доставки ролів і бургерів в Уфа. Багато цікавих акцій на сети
                    і бургери. Безкоштовна доставка по місту при замовленні від 500 р. протягом 45 хвилин. Безкоштовна
                    доставка за межі міста при замовленні від 800 р. протягом 1,5 годин.
                </p>
                <a href="blog.html" class="post__link link">Читати більше</a>
            </div>
            <div class="blog__post post">
                <img src="src/img/blog/2.jpeg" alt="image for post" class="post__image">
                <h5 class="post__description description">Просування шоу вистави "чайка"</h5>
                <address class="post__author">Автор: Вікторія Ренко</address>
                <p class="post__text text">Проект був непростий. Вистава вперше проходив в Росії і аудиторія не знала
                    про нього. Лендінг НЕ конвертувався на самому початку. 2 рази правили на сайті текст і картинки, ще
                    у ФБ був глюк - і кабінет заблокували на добу. Лити на обсязі на вузьке гео теж непросто. Аудиторія
                    вигорає швидко.
                </p>
                <a href="#" class="post__link link">Читати більше</a>
            </div>
            <div class="blog__post post">
                <img src="src/img/blog/4.webp" alt="image for post" class="post__image">
                <h5 class="post__description description">Продаж живих ялинок в Санкт-Петербурзі</h5>
                <address class="post__author">Автор: Кириченко Діма</address>
                <p class="post__text text">Для опту зібрав людей, що займаються сезонним бізнесом по ключових: вікна,
                    двері, пісок, щебінь, бетон і і т.д. А також складаються в блогах: Аяз, бізнес молодість, Верютін,
                    Гандапас, блог Галлії Бердникова, бізнес-ідеї, дропшіппінг, товарні блоги і т.п.
                </p>
                <a href="#" class="post__link link">Читати більше</a>
            </div>
        </div>
    </div>
    <div class="blog__more">
        <a href="#" class="blog__link link">✚ Читати більше</a>
    </div>
</section>

<footer class="footer" id="contacts">
    <div class="footer__wrapper wrapper">
        <div class="footer__info">Агенство Renko</h4>
            <p class="footer__copy text">© 2015 k3nnyart. All rights reserved.</p>
            <a href="#" class="footer__privacy link">Приватна політика</a>
        </div>
        <div class="footer__info footer__address">
            <h5 class="footer__subheading subheading subheading_address">Адреса</h5>
            <p class="footer__text text">Адреса офісу</p>
            <p class="footer__text text">Черкаси, 30 років Перемоги 10 оф5, 18045</p>
            <p class="footer__text text">Графік роботи: Пон-Пт 9:00 - 18:00</p>
        </div>
        <div class="footer__info footer__touch">
            <h5 class="footer__subheading subheading subheading_touch">Контакти</h5>
            <p class="footer__text text">Tel: +380637556672</p>
            <p class="footer__text text">Fax: +380637556672</p>
            <p class="footer__text text">Email: <a href="dmitriy1999745@gmail.com"
                                                   class="footer__mail link">info@yourdomain .com</a></p>
        </div>
        <div class="footer__info">
            <h5 class="footer__subheading subheading">Підпишіться на новосну россилку
            </h5>
            <div class="footer__form form">
                <input type="email" placeholder="Введіть ваш Email"  id="emailSender" class="form_email">
                <button type="submit" class="form_button" onclick="sender()"></button>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
