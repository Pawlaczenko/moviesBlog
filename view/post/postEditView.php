<?php
    $draft = $data['draft'];
    $post = $draft['post'][0];
    $cat =  [];
    foreach($draft['categories'] as $value) {
        array_push($cat,$value->Category_id);
    }
    $names = $draft['names'];
?>
<div class="wrapper addPost">
    <div class="picture-header picture-header--addPost">
        <div class="picture-header__row">
            <svg class="picture-header__icon">
                <use xlink:href="<?=THEME?>/img/sprite.svg#icon-pencil"></use>
            </svg>
            <h1 class="picture-header__h1">Edit your article</h1>
        </div>    
        <p class="picture-header__paragraph">
            Edit your article by changing its title, content, categories or an image.
        </p>
    </div>
    <p class="error_message"><?=(isset($data['post_error']))?Tools::showErrors($data['post_error']):'';?></p>
    <form class="addPostForm" id="formAddPost" action="<?= SITE_PATH . "post/edit"; ?>" method="POST" enctype=multipart/form-data>
        <div class="addPostForm__top">
            <div class="addPostForm__left">
                <label for="addPostTitle" class="addPostForm__label">Title: <input type="text" name="addPostTitle" id="addPostTitle" class="addPostForm__title" required="required" value="<?=isset($post->Title)? htmlentities($post->Title, ENT_QUOTES):'';?>"></label>
                <label for="addPostTitle" class="addPostForm__label">Categories:
                    <select name="category[]" id="addPostForm__category" class="addPostForm__category" multiple required="required">
                        <?php
                            foreach($names as &$value){
                                $mod = (in_array($value->Category_id,$cat))?'selected' :'';
                                $option = '<option '.$mod.' value="'.$value->Category_id.'">'.$value->Name.'</option>';
                                echo $option;
                            }
                        ?>
                    </select>
                </label>
            </div>
            <div class="addPostForm__right" style="background-image: url(<?php
                    echo UPLOADED.'post_headers/'.$post->ImageName;
                ?>);">
                Select your post main image...
                <input type="file" class="addPostForm__file" name="back_photo" />
            </div>
        </div>
        <div class="addPostForm__editor">
            <textarea name="addPostContent" id="addPostContent" required="required">
                <?=isset($post->Content)?$post->Content: '';?>
            </textarea>
        </div>
        <input type="hidden" name="post_id" value="<?=$post->Id;?>">
        <button type="submit" class="button button--addPost addPostForm__button" id="btnAddPost" value="Add">Edit</button>
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
</script>