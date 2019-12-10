<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Agency Creative - Blog</title>
    <link rel="stylesheet" href="/src/style.css">
    <link rel="stylesheet" href="/src/login.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis:500,600|Open+Sans:400,600" rel="stylesheet">

    <script src="//code.jquery.com/jquery-2.1.4.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/jquery.form.min.js"></script>
</head>
<body>

<header class="header header_blog">
    <div class="header__wrapper wrapper">
        <div class="logo"><a class="link" href="/"><img alt="logo" src="/src/img/logo.svg"></a></div>
        <nav class="header__nav nav">
            <input class="nav__check" type="checkbox">
            <span class="nav__line"></span>
            <span class="nav__line"></span>
            <span class="nav__line"></span>
            <ul class="header__menu menu">
                <li class="menu__item"><a href="/" class="link menu__link">Головна</a></li>
                <li class="menu__item"><a href="#services" class="link menu__link">Сервіси</a></li>
                <li class="menu__item"><a href="#blog" class="link menu__link">Блог</a></li>
                <li class="menu__item"><a href="/index.php?method=/account/join" class="link menu__link">Реєстрація</a>
                </li>
                <li class="menu__item"><a href="/index.php?method=/account/login" class="link menu__link">Вхід</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="container">
    <img src="https://dwstroy.ru/lessons/les3373/demo/img/men.png">

    <div id="for_alert"></div>

    <form role="form" id="joinForm" action="#" method="POST">
        <div class="dws-input">
            <input type="text" name="login" placeholder="Введите логин">
        </div>
        <div class="dws-input">
            <input type="password" name="password" placeholder="Введите пароль">
        </div>
        <div class="dws-input">
            <input type="email" name="email" placeholder="Введите Email">
        </div>
        <input class="dws-submit" type="submit" name="submit" value="Зареєструватися"><br/>

    </form>
</div>

</div>


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
            <form action="#" class="footer__form form">
                <input type="email" placeholder="Введіть ваш Email" class="form_email">
                <button type="submit" class="form_button"></button>
            </form>
        </div>
    </div>
</footer>
<script>
    $('#joinForm').ajaxForm({
        url: '/index.php?method=/account/join/reg',
        dataType: 'text',
        success: function (data) {
            data = $.parseJSON(data);
            if (data.error) {
                $('button[type=submit]').prop('disabled', false);
                return showMessage(data.error.message);
            }
            showMessage(data.data.message);
            setTimeout("redirect('/')", 1500);
        },
        beforeSubmit: function (arr, $form, options) {
            $('button[type=submit]').prop('disabled', true);
        }
    });
</script>
</body>
</html>
