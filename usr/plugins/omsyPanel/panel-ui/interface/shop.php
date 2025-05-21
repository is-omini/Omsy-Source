<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Télécharger</h1>
    </div>
    <div class="content">
        <ul>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Thème</a></li>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/plugin" data-update="embed">Plugins</a></li>
            <li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/shop" data-update="embed">Shop</a></li>
        </ul>
    </div>
</section>
<section class="appUiKit-list-plug-card app-kit-main-padding">
    <ul>
        <?php for ($i=0; $i < count($img); $i++): ?>
        <li class="appUiKit-card-plug template-card-space">
            <div class="top">
                <div class="img">
                    <img src="<?= $img[$i] ?>" width="64px">
                </div>
                <div class="content">
                    <span class="name"><?= $title[$i] ?></span>
                    <p><?= rand(2, 10) ?> plugins recommandé</p>
                    <p><?= CMS->GFunction->web_substr($value->description ?? '', 72) ?></p>
                </div>
            </div>
            <div class="bottom">
                <a href="/panel/template?active=<?= $value->id ?>" data-rooter="not">
                    <?php
                    if(CMS->getTemplate() === $value->id) echo 'Installer';
                    else echo 'Télécharger';
                    ?>
                </a>
            </div>
        </li>
        <?php endfor; ?>
    </ul>
</section>
<?= omsp_footer() ?>