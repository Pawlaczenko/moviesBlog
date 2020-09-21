<div class="adminPanel wrapper">
    <div class="adminPanel__container">
        <div class="adminPanel__content">
            <h2 class="adminPanel__header">Users</h2>
            <table class="adminPanel__table display cell-border" id="user-table">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Gender</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Date of birth</th>
                    <th>Avatar name</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                    <?php foreach ($data["users"] as $user) : ?>
                        <tr data-id="<?= $user->Id ?>" class="adminPanel__tr">
                            <td id="manage-id"><?= $user->Id; ?></td>
                            <td id="manage-name"><?= $user->Name; ?></td>
                            <td id="manage-surname"><?= $user->Surname; ?></td>
                            <td><?= $user->Gender; ?></td>
                            <td id="manage-login"><?= $user->Login; ?></td>
                            <td id="manage-email"><?= $user->Email; ?></td>
                            <td><?= $user->DateOfBirth; ?></td>
                            <td><?= $user->PhotoName; ?></td>
                            <td>
                                <form method="POST" action="<?= SITE_PATH . "admin/deleteUser"; ?>" style="text-align: center;">
                                    <input type="hidden" name="id" value="<?=$user->Id?>">
                                    <button class="adminPanel__table-bin">
                                        <svg class="adminPanel__table-svg">
                                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-trash"></use>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="settings adminPanel__settings">
        <section class="settings__row">
            <h2 class="settings__row-header">Settings</h2>
            <form action="<?= SITE_PATH . "admin/editUser"; ?>" method="POST" class="adminPanel__form" enctype=multipart/form-data>
                <label for="settingsImage" class="settings__form-label">
                    <figure class="settings__form-figure" style="background-image: url('<?=ASSETS?>default_male.png')">
                        <svg class="settings__form-figure-svg">
                            <use xlink:href="<?=THEME?>/img/sprite.svg#icon-camera"></use>
                        </svg>
                    </figure>
                    Change avatar...
                    <input type="file" name="avatar" id="settingsImage" class="settings__form-image-input" accept=".png,.jpg,.jpeg">
                </label>
                <label for="settingsName" class="settings__form-label">
                    <span class="settings__form-label-text">Name:</span>
                    <input type="text" name="name" id="name" class="settings__form-text-input" required="required">
                </label>
                <label for="settingsSurname" class="settings__form-label">
                    <span class="settings__form-label-text">Surname:</span>
                    <input type="text" name="surname" id="surname" class="settings__form-text-input" required="required">
                </label>
                <label class="settings__form-label" for="login">
                    <span class="settings__form-label-text">Login:</span>
                    <input type="text" id="login" class="settings__form-text-input" name="login" required="required">
                </label>
                <label for="settingsEmail" class="settings__form-label">
                    <span class="settings__form-label-text">Email:</span>
                    <input type="text" pattern="^[a-z]+([a-z0-9_])+(\.?)[a-z0-9_]+(@{1})+[a-z0-9_]+\.{1}[a-z]+" name="email" id="email" class="settings__form-text-input" required="required">
                </label>
                <div class="form-row">
                    <label class="settings__form-label">Gender</label>
                    <div class="form__radio-container">
                        <input type="radio" class="form__radio-input" id="male" name="gender" required="required" value='m'>
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
                <input type="hidden" name="id" class="hidden">
                <button class="button settings__form-button">Save</button>
            </form>
        </section>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#user-table').DataTable();
    });

    $('.adminPanel__tr').on('click', function(){
        let id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: '<?=ASSETS?>php/getSettings.php',
            data: {
                id: id
            },
            success: function(result){
                let user = JSON.parse(result);
                $('.hidden').val(user[0].Id);
                $('.adminPanel__settings').css('display','flex');
                $('#name').val(user[0].Name);
                $('#surname').val(user[0].Surname);
                $('#email').val(user[0].Email);
                $('#login').val(user[0].Login);
                $(".form__radio-input[value="+user[0].Gender+"]").prop("checked", true);
                let imageUrl = '';

                if(user[0].PhotoName==null || !user[0].PhotoName.length){
                    imageUrl = '<?=ASSETS?>'+'default_male.png';
                } else {
                    imageUrl = '<?=UPLOADED?>'+'users/'+user[0].PhotoName;
                }
                $('.settings__form-figure').css('background-image', 'url(' + imageUrl + ')');

            },
            error: function(res,er){
                console.log(er);
            }
        });
    });

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
        }).appendTo('.adminPanel__form');
    });
</script>