<!-- Page Header -->
<header class="masthead" style="background-image: url('img/post-bg.jpg')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="post-heading">
                    <h1><?= isset($data['siteTitle']) ? $data['siteTitle'] : ''; ?></h1>
                    <h2 class="subheading"><?= isset($data['siteHeading']) ? $data['siteHeading'] : ''; ?></h2>
                    <span class="meta"><?= isset($data['siteMeta']) ? $data['siteMeta'] : ''; ?></span>
                </div>
            </div>
        </div>
    </div>
</header>