<?php
    $users = $data['results']['users'];
    $posts = $data['results']['posts'];
?>
<div class="loader">
  <div class="loader__rotate"></div>
</div>
<div class="searchResults wrapper">
    <section class="searchResults__users">
        <h2 class="profile__info-header searchResults__users-header">
            Users:
        </h2>
        <hr class="line line--rainbow">
        <?=(!count($users)?'<span class="searchResults__none">Nothing found...</span>':'')?>
        <?php foreach($users as $value): ?>

            <a class="searchResults__users-item" href="<?=SITE_PATH.'user/profile/'.$value->Login;?>">
                <figure class="searchResults__users-figure" style="background-image: url(<?php
                    if(isset($value->PhotoName) && strlen($value->PhotoName>0)){
                        echo UPLOADED.'users/'.$value->PhotoName;
                    } else {
                        echo ASSETS.'default_male.png';
                    }
                    ?>);">
                </figure>
                <div class="searchResults__users-info">
                    <p class="searchResults__users-name">
                        <?= $value->Name.' '.$value->Surname?>
                    </p>
                    <p class="searchResults__users-login">
                        <?= $value->Login ?>
                    </p>
                    <p class="searchResults__users-amount">
                        <?= $value->AmountOfPosts ?> posts
                    </p>
                </div>
            </a>

        <?php endforeach; ?>
    </section>
    <section class="searchResults__posts">
        <h2 class="profile__info-header searchResults__posts-header">
            Posts:
        </h2>
        <hr class="line line--rainbow">
        <?=(!count($posts)?'<span class="searchResults__none">Nothing found...</span>':'')?>
        <?php foreach($posts as $value): ?>

            <a class="searchResults__posts-item" href="<?=SITE_PATH.'post/show/'.$value->Id;?>">
                <figure class="searchResults__posts-figure" style="background-image: url(<?php
                        echo UPLOADED.'post_headers/'.$value->ImageName;
                    ?>);">
                </figure>
                <div class="searchResults__posts-info">
                    <p class="searchResults__posts-name">
                        <?= $value->Title?>
                    </p>
                    <p class="searchResults__posts-login">
                        <?= $value->Name.' '.$value->Surname.' - '.$value->Date?>
                    </p>
                    <p class="searchResults__posts-amount">
                        <?= $value->AmountOfLikes ?> likes
                    </p>
                </div>
            </a>

        <?php endforeach; ?>
    </section>
</div>

<script>
    $(window).on('load',(function() {
		$(".loader").fadeOut("slow");
    }));
</script>
