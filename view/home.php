<div class="loader">
  <div class="loader__rotate"></div>
</div>
<section class="header__box wrapper">
    <div class="header__box-white">
        <h2 class="header__box-h">
            news, <br />
            &nbsp;reviews, <br />
            &nbsp;&nbsp;&nbsp;articles.
        </h2>
    </div>
</section>
<section class="filterBox wrapper">
    <form class="filterBox__form" method="POST" action="<?= SITE_PATH . "home/show/1"; ?>">
        <select id="filterBox__category" name="category" class="filterBox__form-category">
            <option value='all' <?php
                if(!isset($data['selected']->Category) || (isset($data['selected']->Category) && $data['selected']->Category=='all')){
                    echo 'selected="selected"';
                }
            ?>
            >All categories</option>
            <?php
                foreach ($data['categories'] as &$value) {
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
<section class="postsGrid wrapper">
    <?php foreach($data['posts'] as $key => $value): ?>

            <?=$categories = '';?>
            <?php foreach($value->Category as $list){
                $categories.='<span class="postsGrid__item-categories-item">';
                $categories.=$list->Name;
                $categories.='</span>';
            } 
            
            $menage = '';?>
            <?php if(isset($_SESSION['isAdmin'])){
                    if($_SESSION['isAdmin']) $menage = "<div class='post__menage'><a href='".SITE_PATH."post/editPost/".$value->Id."' class='post__menage-item'><svg class='post__menage-item-svg'><use xlink:href='".THEME."img/sprite.svg#icon-pencil'></use></svg>Edit</a><a href='".SITE_PATH."post/deletePost/".$value->Id."' class='post__menage-item'><svg class='post__menage-item-svg'><use xlink:href='".THEME."img/sprite.svg#icon-trash'></use></svg>Delete</a></div>";
            } ?>
            
            <div class='postsGrid__container'>
                <a href='<?=SITE_PATH?>post/show/<?=$value->Id?>' class='postsGrid__item'>
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
    <?php endforeach; ?>
</section>

<?php if(count($data['posts'])): ?>
<ul class="pagination wrapper">
    <li class="pagination__item pagination__item--left_r"><a href="?pageno=1"><<</a></li>
    <li class="pagination__item pagination__item--left" class="<?php if($data['pages']['pageno'] <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($data['pages']['pageno'] <= 1){ echo '#'; } else { echo "?pageno=".($data['pages']['pageno'] - 1); } ?>"><</a>
    </li>
    <?php
        // $it = ($data['pages']['total']<=5)?$data['pages']['total']:5;
        if($data['pages']['total']<=5){
           for($i=0; $i<$data['pages']['total']; $i++){
                echo "<li class=\"pagination__item pagination__item-n\"><a href=\"?pageno=".($i+1)."\">".($i+1)."</a></li>";
            } 
        } else {
            $it = $data['pages']['pageno']-2;
            for($i=$it; $i<=$data['pages']['pageno']+2; $i++){
                echo "<li class=\"pagination__item pagination__item-n\"><a href=\"?pageno=".($i)."\">".($i)."</a></li>";
            }
        }
        
    ?>
    <li class="pagination__item pagination__item--right" class="<?php if($data['pages']['pageno'] >= $data['pages']['total']){ echo 'disabled'; } ?>">
        <a href="<?php if($data['pages']['pageno'] >= $data['pages']['total']){ echo '#'; } else { echo "?pageno=".($data['pages']['pageno'] + 1); } ?>">></a>
    </li>
    <li class="pagination__item pagination__item--right_r"><a href="?pageno=<?php echo $data['pages']['total']; ?>">>></a></li>
</ul>
    <?php endif; ?>
<script>
    $(window).on('load',(function() {
		$(".loader").fadeOut("slow");
    }));
    
    $( document ).ready(function() {
        let pagination = $('.pagination__item-n');
        let num = pagination.length;

            let center = Math.ceil(num/2);
            pagination.each(function(i){
                if(i+1<center){
                    $(this).addClass('pagination__item--left');
                } else if(i+1>center){
                    $(this).addClass('pagination__item--right');
                } else{
                    (num%2)?$(this).addClass('pagination__item--center'):$(this).addClass('pagination__item--left');
                }
            });
    });
</script>
