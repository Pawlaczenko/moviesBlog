<!-- Navigation -->
<nav class="nav-wrapper">
    <div class="nav wrapper">
        <a class="nav__logo" href="<?= SITE_PATH; ?>">
            <img src="<?= THEME?>/img/logo black.png" alt="logo" class="nav__logo-item">
        </a>
    </div>
    <div class="burger-box">
        <div class="burger"></div>
    </div>
    <div class="menu visible" id="menu">
        <ul class="menu__list">
            <li class="menu__list__item">
                <a class="menu__list__link" href="<?= SITE_PATH; ?>">Home</a>
            </li>
            <li class="menu__list__item">
                <a class="menu__list__link" href="<?= SITE_PATH; ?>about/">About</a>
            </li>
            <li class="menu__list__item">
                <a class="menu__list__link" href="<?= SITE_PATH; ?>contact/">Contact</a>
            </li>
            <li class="menu__list__item">
                <form action="<?=SITE_PATH?>home/search" method="POST" class="search">
                    <input type="text" class="search__input" name="keyword" value="<?= (isset($data['keyword']))?$data['keyword']:'' ?>">
                    <button class="search__icon">
                        <svg class="search__icon-svg">
                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-magnifying-glass"></use>
                        </svg>
                    </button>
                </form>
            </li>

            <?php if (isset($_SESSION['user_bpawlak'])) : ?>
                <li class="menu__list__item menu__list__item--user menu__list-parent">
                    <a class="menu__list__link menu__list__link--border" href="#"><?= $_SESSION["user_bpawlak"]->Login; ?></a>
                    <figure class="menu__list-avatar" style="background-image: url(<?php
                        if(isset($_SESSION['user_bpawlak']->PhotoName) && strlen($_SESSION['user_bpawlak']->PhotoName>0)){
                            echo UPLOADED.'users/'.$_SESSION['user_bpawlak']->PhotoName;
                        } else {
                            echo ASSETS.'default_male.png';
                        }
                    ?>)">
                    </figure>
                    <ul class="menu__list-submenu">
                        <div class="space"></div>
                        <li class="menu__list-submenu-item">
                            <a href="<?= SITE_PATH; ?>user/profile/<?= $_SESSION['user_bpawlak']->Login; ?>" class="menu__list-submenu-a">
                                My Profile
                                <svg class="menu__list-submenu-svg">
                                    <use xlink:href="<?=THEME?>/img/sprite.svg#icon-user"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="menu__list-submenu-item">
                            <a href="<?= SITE_PATH; ?>post/addPost" class="menu__list-submenu-a">
                                Add Article
                                <svg class="menu__list-submenu-svg">
                                    <use xlink:href="<?=THEME?>/img/sprite.svg#icon-circle-with-plus"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="menu__list-submenu-item">
                            <a href="<?= SITE_PATH; ?>user/settings" class="menu__list-submenu-a">
                                Settings
                                <svg class="menu__list-submenu-svg">
                                    <use xlink:href="<?=THEME?>/img/sprite.svg#icon-cog"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="menu__list-submenu-item">
                            <a href="<?= SITE_PATH; ?>user/logout" class="menu__list-submenu-a">
                                Logout
                                <svg class="menu__list-submenu-svg">
                                    <use xlink:href="<?=THEME?>/img/sprite.svg#icon-log-out"></use>
                                </svg>
                            </a>
                        </li>
                        <?php
                            if(isset($_SESSION["isAdmin"]) AND $_SESSION["isAdmin"])echo '<li class="menu__list-submenu-item"><a href="'.SITE_PATH.'admin/panel/" class="menu__list-submenu-a menu__list-submenu--admin">Admin panel<svg class="menu__list-submenu-svg"><use xlink:href="'.THEME.'/img/sprite.svg#icon-eye"></use></svg></a></li>';
                        ?>
                    </ul>
                </li>
            <?php else : ?>
                <li class="menu__list__item">
                    <a class="menu__list__link" href="<?= SITE_PATH; ?>user/login">Login</a>
                </li>
                <li class="menu__list__item menu__list__item--border">
                    <a class="menu__list__link menu__list__link--border" href="<?= SITE_PATH; ?>user/register">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>