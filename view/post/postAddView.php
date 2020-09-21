<div class="wrapper addPost">
    <div class="picture-header picture-header--addPost">
        <div class="picture-header__row">
            <svg class="picture-header__icon">
                <use xlink:href="<?=THEME?>/img/sprite.svg#icon-text-document"></use>
            </svg>
            <h1 class="picture-header__h1">Create an article</h1>
        </div>    
        <p class="picture-header__paragraph">
            Write whatever is in your head an upload it on our page.
        </p>
    </div>
    <p class="error_message"><?=(isset($data['post_error']))?Tools::showErrors($data['post_error']):'';?></p>
    <form class="addPostForm" id="formAddPost" action="<?= SITE_PATH . "post/add"; ?>" method="POST" enctype=multipart/form-data>
        <div class="addPostForm__top">
            <div class="addPostForm__left">
                <label for="addPostTitle" class="addPostForm__label">Title: <input type="text" name="addPostTitle" id="addPostTitle" class="addPostForm__title" required="required" value="<?=isset($data['form_data']['title'])?htmlentities($data['form_data']['title'], ENT_QUOTES) : '';?>"></label>
                <label class="addPostForm__label">Categories:
                    <select name="category[]" id="addPostForm__category" class="addPostForm__category" multiple required="required">
                        <?php
                            foreach ($data['categories'] as &$value) {
                                $option = '<option value="'.$value->Category_id.'">'.$value->Name.'</option>';
                                echo $option;
                            }
                        ?>
                    </select>
                </label>
            </div>
            <div class="addPostForm__right">
                Select your post main image...
                <input type="file" class="addPostForm__file" name="back_photo" required="required" />
            </div>
        </div>
        <div class="addPostForm__editor">
            <textarea name="addPostContent" id="addPostContent" required="required">
                <?=isset($data['form_data']['content'])?$data['form_data']['content'] : '';?>
            </textarea>
        </div>
        <button type="submit" class="button button--addPost addPostForm__button" id="btnAddPost" value="Add">Add +</button>
    </form>
</div>
<script>
    $("#addPostForm__category").chosen({
        width: "30%",
        max_selected_options: 3,
        placeholder_text_multiple: "Choose categories (max 3)"
    });

    CKEDITOR.replace( 'addPostContent', {
        filebrowserUploadUrl: 'http://moje-portfolio.pl/bartlomiej_pawlak/moviesBlog/core/image_uploader.php?destiny=1',
        filebrowserUploadMethod: 'form'
    } );

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('.addPostForm__right').css("background-image", "url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".addPostForm__file").change(function() {
        readURL(this);
    });

    //  $("#btnAddPost").on('click', function (event) {  
    //        var el = $(this);
    //        el.prop('disabled', true);
    //        setTimeout(function(){el.prop('disabled', false); }, 10000);
    //  });
    // // Enable navigation prompt
    // window.onbeforeunload = function() {
    //     return true;
    // };
</script>