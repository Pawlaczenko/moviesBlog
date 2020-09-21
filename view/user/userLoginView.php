<div class="blured_background blured_background--login"></div>
<div class="wrapper">
    <div class="form__window">
        <div class="form__window-left form__window-left--login">
            <h1 class="form__window-left-header">
                Login<br/>Form.
            </h1>
            <p class="form__window-left-p">
                Dolor mollit nulla reprehenderit laboris Lorem id amet ea reprehenderit in culpa aute amet id.
            </p>
        </div>
        <div class="form__window-right">
            <h2 class="form__window-right-header">Login</h2>
            <p class="form__window-right-p">Zaloguj się i dodawaj posty. Jeżeli nie posiadasz konta <a href="<?= SITE_PATH; ?>user/register">zarejestruj się</a>.</p>
            <p class="error_message"><?=isset($data['login_message'])?Tools::showErrors($data['login_message']):'';?></p>
            <form class="form" id="formLogin" action="<?= SITE_PATH . "user/login/"; ?>" method="POST">
                <div class="form-row">
                    <input type="text" pattern="^(?=.*[A-Za-z0-9]$)[A-Za-z][A-Za-z\d.-]{0,19}$" class="form-input" name="uname1" id="uname1" required="required" placeholder="Login" value="<?=(isset($data['login']))?$data['login']:''?>">
                </div>
                <div class="form-row">
                    <input type="password" class="form-input" name="pwd1" id="pwd1" required="required" placeholder="Password">
                </div>
                <button type="submit" class="button button--login" id="btnLogin">Login</button>
            </form>
        </div>
        <!--/col-->
    </div>
    <!--/row-->
</div>