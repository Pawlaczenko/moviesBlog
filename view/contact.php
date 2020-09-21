<div class="blured_background blured_background--contact"></div>
<div class="wrapper">
    <div class="form__window">
        <div class="form__window-left form__window-left--contact">
            <h1 class="form__window-left-header">
                Contact<br/>Form.
            </h1>
            <p class="form__window-left-p">
                Dolor mollit nulla reprehenderit laboris Lorem id amet ea reprehenderit in culpa aute amet id.
            </p>
        </div>
        <div class="form__window-right">
            <h2 class="form__window-right-header">Contact us</h2>
            <p class="form__window-right-p">Write us a message and we will response.</p>
            <form class="form" id="formContact" action="#" method="POST">
                <div class="form-row">
                    <input type="text" class="form-input" name="name" id="name" required="required" placeholder="Your name" value="<?=(isset($_SESSION['user_bpawlak']))?$_SESSION['user_bpawlak']->Name.' '.$_SESSION['user_bpawlak']->Surname:''?>">
                </div>
                <div class="form-row">
                    <input type="text" class="form-input" name="email" pattern="^[a-z]+([a-z0-9_])+(\.?)[a-z0-9_]+(@{1})+[a-z0-9_]+\.{1}[a-z]+" id="email" required="required" placeholder="Your E-mail" value="<?=(isset($_SESSION['user_bpawlak']))?$_SESSION['user_bpawlak']->Email:''?>">
                </div>
                <div class="form-row">
                    <textarea name="message" class="form-textarea" id="message" placeholder="Your message..." required="required"></textarea>
                </div>
                <button type="submit" class="button button--login" id="btnContact">Send</button>
            </form>
        </div>
        <!--/col-->
    </div>
    <!--/row-->
</div>