<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Liste de thèmes</h1>
    </div>
    <div class="content">
        <ul>
            <li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Thème</a></li>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/plugin" data-update="embed">Plugins</a></li>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/shop" data-update="embed">Shop</a></li>
        </ul>
    </div>
</section>
<section class="appUiKit-list-plug-card app-kit-main-padding">
    <ul>
        <?php foreach ($buff as $key => $value): ?>
        <li class="appUiKit-card-plug template-card-space">
            <div class="top">
                <div class="img">
                    <img src="<?= $value->thumbnail ?>" width="64px">
                </div>
                <div class="content">
                    <span class="name"><?= $value->name ?></span>
                    <p><?= count($value->install->plugin) ?> plugins recommandé</p>
                    <p><?= CMS->GFunction->web_substr($value->description ?? '', 72) ?></p>
                </div>
            </div>
            <div class="bottom">
                <a href="getter?plugin=omsyPanel&page=template&active=<?= $value->id ?>" data-rooter="not">
                    <?php
                    if(CMS->getTemplate() === $value->id) echo 'Désactiver';
                    else echo 'Activer';
                    ?>
                </a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<?= omsp_footer() ?>