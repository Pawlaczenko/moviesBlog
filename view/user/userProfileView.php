<?php
    $profileInfo = $data['profile']['user'];
    $profilePosts = $data['profile']['posts'];
    $categories = $data['profile']['categories'];
?>
<div class="profile wrapper">
    <div class="profile__header">
        <figure class="profile__header-figure" style="background-image: url(<?php
                    if(isset($profileInfo->PhotoName) && strlen($profileInfo->PhotoName>0)){
                        echo UPLOADED.'users/'.$profileInfo->PhotoName;
                    } else {
                        echo ASSETS.'default_male.png';
                    }
                ?>);">
        </figure>
        <div class="profile__header-info">
            <div class="profile__header-name"><?= $profileInfo->Name.' '.$profileInfo->Surname; ?></div>
            <div class="profile__header-login"><?=$profileInfo->Login?></div>
        </div>  
    </div>
    <!-- <hr class="line line--rainbow"> -->
    <div class="profile__container">
        <div class="profile__content">
            <div class="profile__info profile__row">
                <h2 class="profile__info-header">
                    Profile Information
                </h2>
                <hr class="line line--rainbow">
                <table class="profile__info-table">
                    <tr>
                        <td class="profile__info-table-h">Name</td>
                        <td class="profile__info-table-p"><?= $profileInfo->Name;?></td>
                    </tr>
                    <tr>
                        <td class="profile__info-table-h">Surname</td>
                        <td class="profile__info-table-p"><?= $profileInfo->Surname; ?></td>
                    </tr>
                    <tr>
                        <td class="profile__info-table-h">E-Mail</td>
                        <td class="profile__info-table-p"><?= $profileInfo->Email; ?></td>
                    </tr>
                    <tr>
                        <td class="profile__info-table-h">Username</td>
                        <td class="profile__info-table-p"><?= $profileInfo->Login; ?></td>
                    </tr>
                    <tr>
                        <td class="profile__info-table-h">Date of birth</td>
                        <td class="profile__info-table-p"><?= $profileInfo->DateOfBirth; ?></td>
                    </tr>
                    <tr>
                        <td class="profile__info-table-h">Likes</td>
                        <td class="profile__info-table-p"><?=$data['likes']?></td>
                    </tr>
                </table>
            </div>
            <div class="profile__posts profile__row">
                <h2 class="profile__info-header">
                    <?=$profileInfo->Name;?>'s articles
                </h2>
                <hr class="line line--rainbow">
                <section class="filterBox">
                    <form class="filterBox__form" method="POST" action="<?= SITE_PATH . "user/profile/".$profileInfo->Login."/1"; ?>">
                        <select id="filterBox__category" name="category" class="filterBox__form-category">
                            <option value='all' <?php
                            if(!isset($data['selected']->Category) || (isset($data['selected']->Category) && $data['selected']->Category=='all')){
                                echo 'selected="selected"';
                            }
                        ?>>All categories</option>
                            <?php
                                foreach ($categories as &$value) {
                                    $selected = (isset($data['selected']->Category) && $data['selected']->Category==$value->Category_id)? 'selected="selected" ':'';
                                    $option = '<option '.$selected.'value="'.$value->Category_id.'">'.$value->Name.'</option>';
                                    echo $option;
                                }
                            ?>
                        </select>
                        <button class="filterBox__form-button">sort</button>
                        <select id="filterBox__form-category" name="order" class="filterBox__form-category">
                            <option value='new' <?php
                                if(!isset($data['selected']->Order) || (isset($data['selected']->Order) && $data['selected']->Order=='new')){
                                    echo 'selected="selected"';
                                }
                            ?>>Newest</option>
                            <option value='old' <?=(isset($data['selected']->Order) && $data['selected']->Order=='old')? 'selected="selected" ':'';?> >Oldest</option>
                            <option value='alpA' <?=(isset($data['selected']->Order) && $data['selected']->Order=='alpA')? 'selected="selected" ':'';?>>A - Z</option>
                            <option value='alpZ' <?=(isset($data['selected']->Order) && $data['selected']->Order=='alpZ')? 'selected="selected" ':'';?>>Z - A</option>
                            <option value='lik' <?=(isset($data['selected']->Order) && $data['selected']->Order=='lik')? 'selected="selected" ':'';?>>Most Likes</option>
                            <option value='com' <?=(isset($data['selected']->Order) && $data['selected']->Order=='com')? 'selected="selected" ':'';?>>Most Comments</option>
                        </select>
                    </form>
                </section>
                <section class="postsGrid">
                    <?php if($profilePosts): ?>
                        <?php $counter = 0; ?>
                        <?php foreach($profilePosts as $key => $value): ?>

                            <?php
                            $categories = '';
                            foreach($value->Category as $list){
                                $categories.='<span class="postsGrid__item-categories-item">';
                                $categories.=$list->Name;
                                $categories.='</span>';
                            }

                            $menage = '';
                            if(isset($_SESSION['user_bpawlak'])){
                                if($profileInfo->Id == $_SESSION['user_bpawlak']->Id || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
                                    $menage = "<div class='post__menage'><a href='".SITE_PATH."post/editPost/".$value->Id."' class='post__menage-item prevent-click'><svg class='post__menage-item-svg'><use xlink:href='".THEME."img/sprite.svg#icon-pencil'></use></svg>Edit</a><a href='".SITE_PATH."post/deletePost/".$value->Id."' class='post__menage-item'><svg class='post__menage-item-svg'><use xlink:href='".THEME."img/sprite.svg#icon-trash'></use></svg>Delete</a></div>";
                                }
                            }
                            ?>

                            <div class='postsGrid__container'>
                                <a href="<?=SITE_PATH?>post/show/<?=$value->Id?>" class='postsGrid__item'>
                                    <div class='postsGrid__item-image postsGrid__item-image--profile' style="background-image: url('<?=UPLOADED?>post_headers/<?=$value->ImageName?>')"></div>
                                    <h3 class='postsGrid__item-title'><?=$value->Title?></h3>
                                    <div class='postsGrid__item-categories'><?=$categories?></div>
                                    <div class='postsGrid__item-footer'>
                                        <div class='postsGrid__item-date'><?=$value->Date?></div>
                                        <div class='postsGrid__item-like'>
                                            <svg class='postsGrid__item-like-svg'>
                                                <use xlink:href="<?=THEME?>img/sprite.svg#icon-thumbs-up"></use>
                                            </svg><?=$value->AmountOfLikes?>
                                        </div>
                                        <div class='postsGrid__item-like'>
                                            <svg class='postsGrid__item-like-svg'>
                                                <use xlink:href="<?=THEME?>img/sprite.svg#icon-chat"></use>
                                            </svg><?=$value->AmountOfComments?>
                                        </div>
                                    </div>
                                </a>
                                <?=$menage?>
                            </div>
                        
                        <?php endforeach;?>
                    <?php else: ?>
                        <div class="postsGrid__empty"><?=$profileInfo->Name?> nie dodał(a) jeszcze żadnych postów</div>; 
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</div>