<div class="adminPanel wrapper">
    <div class="picture-header picture-header--admin">
        <div class="picture-header__row">
            <svg class="picture-header__icon">
                <use xlink:href="<?=THEME?>/img/sprite.svg#icon-eye"></use>
            </svg>
            <h1 class="picture-header__h1">Admin panel</h1>
        </div>    
        <p class="picture-header__paragraph">
            Menage the whole page as you wish.
        </p>
    </div>
    <div class="adminPanel__navigation">
        <a href="<?=SITE_PATH?>admin/panel/users" class="adminPanel__navigation-item <?=($data['path']=='users')?'adminPanel__navigation-item--current':''?>">users</a>
        <a href="<?=SITE_PATH?>admin/panel/logs" class="adminPanel__navigation-item <?=($data['path']=='logs')?'adminPanel__navigation-item--current':''?>">Logs</a>
        <a href="<?=SITE_PATH?>admin/panel/categories" class="adminPanel__navigation-item <?=($data['path']=='categories')?'adminPanel__navigation-item--current':''?>">Categories</a>
    </div>
</div>