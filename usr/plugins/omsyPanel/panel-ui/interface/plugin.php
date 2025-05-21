<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Liste de plugins</h1>
    </div>
    <div class="content">
        <ul>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Thème</a></li>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/plugin" data-update="embed" class="active">Plugins</a></li>
            <li><a href="/panel/getter?plugin=omsyPanel&page=/shop" data-update="embed">Shop</a></li>
        </ul>
    </div>
</section>
<section class="appUiKit-list-plug-card app-kit-main-padding">
    <ul>
        <?php foreach ($buff as $key => $value): ?>
        <li class="appUiKit-card-plug">
            <div class="top">
                <div class="img">
                    <img src="<?= $value->thumbnail ?>" width="64px">
                </div>
                <div class="content">
                    <span class="name"><?= $value->name ?></span>
                    <p><?= CMS->GFunction->web_substr($value->description, 72) ?></p>
                </div>
            </div>
            <div class="bottom">
                <a href="/panel/template?active=<?= $value->id ?>" data-rooter="not">Activer maintenant</a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<?= omsp_footer() ?>