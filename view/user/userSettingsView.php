<div class="wrapper settings">
    <div class="picture-header picture-header--settings">
        <div class="picture-header__row">
            <svg class="picture-header__icon">
                <use xlink:href="<?=THEME?>/img/sprite.svg#icon-cog"></use>
            </svg>
            <h1 class="picture-header__h1">Account settings</h1>
        </div>    
        <p class="picture-header__paragraph">
            Change your data and menage your profile.
        </p>
    </div>

    <section class="settings__row">
        <h2 class="settings__row-header">Personal info</h2>
        <p class="error_message error_message--settings"><?=(isset($data['update_message']))?Tools::showErrors($data['update_message']):'';?></p>
        <form action="<?= SITE_PATH . "user/editUser"; ?>" method="POST" class="settings__form" enctype=multipart/form-data>
            <label for="settingsImage" class="settings__form-label">
                <figure class="settings__form-figure" style="background-image: url(<?php
                    if(isset($_SESSION['user_bpawlak']->PhotoName) && strlen($_SESSION['user_bpawlak']->PhotoName>0)){
                        echo UPLOADED.'users/'.$_SESSION['user_bpawlak']->PhotoName;
                    } else {
                        echo ASSETS.'default_male.png';
                    }
                ?>)">
                    <svg class="settings__form-figure-svg">
                        <use xlink:href="<?=THEME?>/img/sprite.svg#icon-camera"></use>
                    </svg>
                </figure>
                Change your avatar...
                <input type="file" name="avatar" id="settingsImage" class="settings__form-image-input" accept=".png,.jpg,.jpeg">
            </label>
            <label for="settingsName" class="settings__form-label">
                <span class="settings__form-label-text">Name:</span>
                <input type="text" name="name" id="settingsName" class="settings__form-text-input" value="<?=$_SESSION['user_bpawlak']->Name?>" required="required">
            </label>
            <label for="settingsSurname" class="settings__form-label">
                <span class="settings__form-label-text">Surname:</span>
                <input type="text" name="surname" id="settingsSurname" class="settings__form-text-input" value="<?=$_SESSION['user_bpawlak']->Surname?>" required="required">
            </label>
            <label for="settingsEmail" class="settings__form-label">
                <span class="settings__form-label-text">Email:</span>
                <input type="text" pattern="^[a-z]+([a-z0-9_])+(\.?)[a-z0-9_]+(@{1})+[a-z0-9_]+\.{1}[a-z]+" name="email" id="settingsEmail" class="settings__form-text-input" value="<?=$_SESSION['user_bpawlak']->Email?>" required="required">
            </label>
            <div class="form-row">
                <label class="settings__form-label">Gender</label>
                <div class="form__radio-container">
                    <input type="radio" class="form__radio-input" id="male" name="gender" required="required" value='m' <?=($_SESSION['user_bpawlak']->Gender=='m')? "checked" : '' ?>>
                    <label for="male" class="form__radio-label">
                        <span class="form__radio-button"></span>
                        Male
                    </label>
                </div>
                <div class="form__radio-container">
                    <input type="radio" class="form__radio-input" id="female" name="gender" required="required" value='f' <?=($_SESSION['user_bpawlak']->Gender=='f')? "checked":'' ?>>
                    <label for="female" class="form__radio-label">
                        <span class="form__radio-button"></span>
                        Female
                    </label>
                </div>
                <div class="form__radio-container">
                    <input type="radio" class="form__radio-input" id="other" name="gender" required="required" value='o' <?=($_SESSION['user_bpawlak']->Gender=='o')? "checked":'' ?>>
                    <label for="other" class="form__radio-label">
                        <span class="form__radio-button"></span>
                        Other
                    </label>
                </div>
            </div>
            <button class="button settings__form-button">Save</button>
        </form>
        <hr class="line line--rainbow">
    </section>

    <section class="settings__row">
        <h2 class="settings__row-header">Change password</h2>
        <p class="error_message error_message--settings"><?=(isset($data['reset']))?Tools::showErrors($data['reset']):'';?></p>
        <form method="POST" action="<?= SITE_PATH . "user/resetPassword"; ?>" class="settings__form">
            <label for="currentPassword" class="settings__form-label">
                <span class="settings__form-label-text">Current password:</span>
                <input type="password" name="current" id="currentPassword" class="settings__form-text-input" required="required" autocomplete="off">
            </label>
            <label for="newPassword1" class="settings__form-label">
                <span class="settings__form-label-text">New password:</span>
                <input type="password" name="new1" id="newPassword1" class="settings__form-text-input" required="required" autocomplete="off">
            </label>
            <label for="newPassword2" class="settings__form-label">
                <span class="settings__form-label-text">Repeat password:</span>
                <input type="password" name="new2" id="newPassword2" class="settings__form-text-input" required="required" autocomplete="off">
            </label>
            <button class="button settings__form-button">Reset</button>
        </form>
        <hr class="line line--rainbow">
    </section>

    <section class="settings__row">
        <h2 class="settings__row-header">Delete your account</h2>
        <p class="error_message error_message--settings"><?php if(isset($data['delete'])){
                echo $data['delete'];
            }; ?></p>
        <form method="POST" action="<?= SITE_PATH . "user/deleteUser"; ?>" class="settings__form">
            <label for="password" class="settings__form-label">
                <span class="settings__form-label-text">Your password:</span>
                <input type="password" name="password" id="password" class="settings__form-text-input" required="required" autocomplete="off">
            </label>
            <label for="assurance" class="settings__form-label">
                <span class="settings__form-label-text">I'm sure:</span>
                <input type="checkbox" id="assurance" class="settings__form-checkbox-input">
            </label>
            <button class="button settings__form-button settings__form-delete" disabled>Delete</button>
        </form>
    </section>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('.settings__form-figure').css("background-image", "url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".settings__form-image-input").change(function() {
        readURL(this);
        $('<input>').attr({
            type: 'hidden',
            id: 'foo',
            name: 'change',
            value: true
        }).appendTo('.settings__form');
    });

    $('#assurance').change(function() {
        if($(this).is(":checked")){
            $('.settings__form-delete').attr("disabled", false);
        } else {
            $('.settings__form-delete').attr("disabled", true);
        }
    });
</script>