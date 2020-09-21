<?php
    $post = $data['post'][0];
    $comments = $data['comments'];
?>
<div class="showPost__header" style="background-image: url('<?php echo UPLOADED; ?>post_headers/<?php echo $post->ImageName;?>')"></div>

<section class="showPost wrapper">
    <div class="showPost__categories">
        <?php if(count($post->Category)): ?>
            <?php foreach($post->Category as $value): ?>
                <div class="showPost__categories-item"><?=$value->Name?></div>
            <?php endforeach?>
        <?php else: ?>
            <div class="showPost__categories-item">Brak kategorii</div>
        <?php endif?>
    </div>
    <h1 class="showPost__title"><?= $post->Title; ?></h1>
    <div class="showPost__subTitle">
        By: 
        <a class="showPost__subTitle-author" href="
            <?php 
                if(isset($post->Login)){
                    echo SITE_PATH.'user/profile/'.$post->Login.'"';
                }else {
                    echo '#" style="border: none; cursor:text;"';
                }
            ?>
        ><?php 
                if(isset($post->Login)){
                    echo $post->Name.' '.$post->Surname;
                } else {
                    echo 'usunięty';
                }
            ?></a>
        &middot;
        <span class="showPost__subTitle-date"><?= $post->Date; ?></span>
    </div>
    <article class="showPost__content cke_contents_ltr cke_editable">
        <?= $post->Content; ?>
    </article>
    <section class="showPost__likes">
        <span class="showPost__likes-amount"><?=$post->AmountOfLikes;?></span> likes
        <?php if(isset($_SESSION['user_bpawlak'])): ?>

        <a class="showPost__likes-like" id="like_button" data-id="<?=$post->Id?>">
            <svg class="showPost__likes-svg <?php
                    echo ($data['isLiked'])?'showPost__likes-svg--liked':'';   
                ?>">
                <use xlink:href="<?=THEME?>/img/sprite.svg#icon-thumbs-up"></use>
            </svg>    
        </a>

        <?php endif; ?>     
    </section>
    <section class="showPost__comments">
        <h3 class="showPost__comments-header"><span class="showPost__comments-counter"><?=count($comments)?></span> comments</h3>
        <hr class="line line--rainbow">
        <?php if(isset($_SESSION['user_bpawlak'])): ?>
            <div class="showPost__comments-add">
                <figure class="showPost__comments-avatar" style="background-image: url(<?php
                    if(isset($_SESSION['user_bpawlak']->PhotoName) && strlen($_SESSION['user_bpawlak']->PhotoName>0)){
                        echo UPLOADED.'users/'.$_SESSION['user_bpawlak']->PhotoName;
                    } else {
                        echo ASSETS.'default_male.png';
                    }
                ?>)">
                </figure>
                <form action="#" method="POST" class="showPost__comments-form" id="addComment">
                    <div class="showPost__comments-help">
                        <textarea maxlength="500" name="comment" id="comment" cols="30" rows="10" placeholder="Join the discussion..." class="showPost__comments-form-cm"></textarea>
                        <div id="charNum" class="showPost__comments-form-cc"></div>
                    </div>
                    <input type="hidden" name="post" id="commentPost" value="<?=$post->Id?>">
                    <button class="button button--comment">Add</button>
                </form>
            </div>
            <p class="error_message"></p>
        <?php endif; ?>
        <div class="showPost__comments-list">
        <?php foreach($comments as $comment): ?>
        
            <div class="showPost__comment">
                <figure class="showPost__comments-avatar" style="background-image: url(<?php
                    if(isset($comment->PhotoName) && strlen($comment->PhotoName>0)){
                        echo UPLOADED.'users/'.$comment->PhotoName;
                    } else {
                        echo ASSETS.'default_male.png';
                    }
                ?>)">
                </figure>
                <div class="showPost__comment-content">
                    <div class="showPost__comment-user">
                        <a class="showPost__comment-name" href="<?=SITE_PATH.'user/profile/'.$comment->Login;?>">
                            <?=$comment->Name.' '.$comment->Surname?>
                        </a>
                        <div class="showPost__comment-date">
                            <?=$comment->Date?>
                        </div>
                    </div>
                    <div class="showPost__comment-text">
                        <?=nl2br($comment->Comment)?>
                    </div>
                </div>
                <?php if(isset($_SESSION['user_bpawlak']) && ($_SESSION['user_bpawlak']->Login == $comment->Login || $_SESSION['isAdmin'] || $_SESSION['user_bpawlak']->Login == $post->Login)): ?>
                <div class="showPost__comment-menage">
                    <span data-id="<?=$comment->Id?>" class="showPost__comment-delete">[DELETE]</span>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach;?> 
        </div>
    </section>
</section>

<script>

    $('#comment').keyup(function () {
        var max = 500;
        var len = $(this).val().length;
        if (len >= max) {
            $('#charNum').text('You have reached the limit');
        } else {
            var char = max - len;
            $('#charNum').text(char + ' characters left');
        }
    });

    $('.showPost__comments-list').on('click', '.showPost__comment-delete', function(e) {
        let id = $(this).data('id');
        let com = $(this).closest('.showPost__comment');
        console.log($(this).data('id'));
        com.fadeOut("normal", function() {
            com.remove();
        });
        $.ajax({
            type: 'POST',
            url: '<?=ASSETS?>php/comment.php',
            data: {
                id: id,
                delete: 1
            },
            success: function(result){
                console.log(result);
            },
            error: function(res,er){
                console.log('er');
            }
        });
    });

    $("#like_button").on('click',function(event){
        let post = $(this).data('id');
        <?php if(isset($_SESSION['user_bpawlak'])): ?> let user = <?= $_SESSION['user_bpawlak']->Id ?> <?php endif;?>;
        let func;
        if($('.showPost__likes-svg').hasClass('showPost__likes-svg--liked')){
            func = 1;
        } else {
            func = 0;
        }
        $.ajax({
            type: 'POST',
            url: '<?=ASSETS?>php/like.php',
            data: {
                id: post,
                user: user,
                func: func
            },
            success: function(result){
                $('.showPost__likes-amount').text(result);
                $('.showPost__likes-svg').toggleClass('showPost__likes-svg--liked');
            },
            error: function(res,er){
                console.log('er');
            }
        });
    });

    function generateComment(comment) {
        let path = '';
        console.log(comment[0].PhotoName);
        if(typeof comment[0].PhotoName === 'undefined' || comment[0].PhotoName==''){
            path = '<?=ASSETS?>default_male.png';
        } else {
            path = '<?=UPLOADED?>users/'+comment[0].PhotoName;
        }

        let result = `
                <div class="showPost__comment">
                    <figure class="showPost__comments-avatar" style="background-image: url('${path}')"></figure>
                    <div class="showPost__comment-content">
                        <div class="showPost__comment-user">
                            <a class="showPost__comment-name" href="<?=SITE_PATH?>user/profile/${comment[0].Login}">${comment[0].Name} ${comment[0].Surname}</a>
                            <div class="showPost__comment-date">${comment[0].Date}</div>
                        </div>
                        <div class="showPost__comment-text">
                            ${comment[0].Comment.replace(/\n/g,"<br>")}
                        </div>
                    </div>
                    <div class="showPost__comment-menage">
                        <span data-id="${comment[0].Id}" class="showPost__comment-delete">[DELETE]</span>
                    </div>
                </div>`;
        return result;
    }

    $("#addComment").on("submit", function (e) {
        e.preventDefault();
        let content = $('#comment').val();
        let post = $('#commentPost').val();

        if(content.length>0 && content.length<=500){
            $('#comment').val('');
            $('#charNum').text('');
            $('.error_message').text('');
            $.ajax({
                type: 'POST',
                url: '<?=ASSETS?>php/comment.php',
                data: {
                    post: post,
                    content: content
                },
                success: function(result){
                    let recent = JSON.parse(result);
                    let single = generateComment(recent);
                    $('.showPost__comments-list').prepend(single);
                    //console.log(result);
                },
                error: function(res,er){
                    console.log('er');
                }
            });
        } else {
            $('.error_message').append('Komentarz może mieć najwyżej 500 znaków.');
        } 
    });
</script>