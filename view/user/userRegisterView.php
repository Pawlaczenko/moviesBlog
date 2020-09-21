<div class="blured_background blured_background--registration"></div>
<div class="wrapper">
    <div class="form__window">
        <div class="form__window-left form__window-left--register">
            <h1 class="form__window-left-header">
                Registration<br/>Form.
            </h1>
            <p class="form__window-left-p">
                Dolor mollit nulla reprehenderit laboris Lorem id amet ea reprehenderit in culpa aute amet id.
            </p>
        </div>

        <div class="form__window-right">
            <h2 class="form__window-right-header">Register</h2>
            <p class="form__window-right-p">Dołącz do naszego bloga i zacznij pisać własne posty. Jeśli posiadasz już konto <a href="<?= SITE_PATH; ?>user/login">zaloguj się</a>.</p>
            <p class="error_message error_message--register"><?=Tools::showErrors($data['register_message'])?></p>
            <form class="form" id="formRegister" action="<?= SITE_PATH . "user/register"; ?>" method="POST">
                <div class="form-row">
                    <input type="text" pattern="^(?=.*[A-Za-z0-9]$)[A-Za-z][A-Za-z\d.-]{0,19}$" class="form-input" name="login" id="login" required="required" placeholder="Login" value="<?= isset($data['form_data']['login']) ? $data['form_data']['login'] : ''; ?>">
                    <div class="tooltip">
                        <svg class="tooltip__icon">
                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-info-with-circle"></use>
                        </svg>
                        <span class="tooltip__text">Login musi składać się z więcej niż 5 i mniej niż 12 znaków.</span>
                    </div> 
                </div>
                <div class="form-row">
                    <input type="email" pattern="^[a-z]+([a-z0-9_])+(\.?)[a-z0-9_]+(@{1})+[a-z0-9_]+\.{1}[a-z]+" class="form-input" name="email" id="email" required="required" placeholder="Email" value="<?= isset($data['form_data']['email']) ? $data['form_data']['email'] : ''; ?>">
                </div>
                <div class="form-row">
                    <input type="password" class="form-input" name="pwd1" id="pwd1" required="required" placeholder="Password">
                    <div class="tooltip">
                        <svg class="tooltip__icon">
                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-info-with-circle"></use>
                        </svg>
                        <span class="tooltip__text">Hasło musi być dłuższe niż 6 znaków. Hasło musi posiadać chcoć jedną: małą literę, dużą literę, cyfrę.</span>
                    </div>
                </div>
                <div class="form-row">
                    <input type="password" class="form-input" name="pwd2" id="pwd2" required="required" placeholder="Repeat Password">
                </div>
                <div class="form-row">
                    <input type="text" class="form-input" name="name" id="name" required="required"  placeholder="Name" value="<?= isset($data['form_data']['name']) ? $data['form_data']['name'] : ''; ?>">
                </div>
                <div class="form-row">
                    <input type="text" class="form-input" name="surname" id="surname" required="required" placeholder="Surname" value="<?= isset($data['form_data']['surname']) ? $data['form_data']['surname'] : ''; ?>">
                </div>
                <div class="form-row">
                    <label class="form-label">Gender</label>
                    <div class="form__radio-container">
                        <input type="radio" class="form__radio-input" id="male" name="gender" required="required" value='m' required="required">
                        <label for="male" class="form__radio-label">
                            <span class="form__radio-button"></span>
                            Male
                        </label>
                    </div>
                    <div class="form__radio-container">
                        <input type="radio" class="form__radio-input" id="female" name="gender" required="required" value='f'>
                        <label for="female" class="form__radio-label">
                            <span class="form__radio-button"></span>
                            Female
                        </label>
                    </div>
                    <div class="form__radio-container">
                        <input type="radio" class="form__radio-input" id="other" name="gender" required="required" value='o'>
                        <label for="other" class="form__radio-label">
                            <span class="form__radio-button"></span>
                            Other
                        </label>
                    </div>
                </div>
                <div class="form-row date-row">
                    <label for="birth" class="form-label">Date of birth</label>
                    <div class="date-inputs">
                        <select name="day" id="day" class="date-inputs-select">
                            <?php
                                for($i=1; $i<=31; $i++){
                                    echo '<option value='.$i.'>'.$i.'</option>';
                                }
                            ?>
                        </select>
                        <select name="month" id="month" class="date-inputs-select">
                            <?php
                                $months = array("January", "February", "March", "April", "May", "June","July","August","September","October","November","December");
                                for($i=0; $i<12; $i++){
                                    echo '<option value='.($i+1).'>'.$months[$i].'</option>';
                                }
                            ?>
                        </select>
                        <select name="year" id="year" class="date-inputs-select">
                            <?php
                                for($i=date("Y"); $i>=1910; $i--){
                                    echo '<option value='.$i.'>'.$i.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="button button--register" id="btnRegister" value="Register">Register</button>
            </form>
        </div>
    </div>
</div>