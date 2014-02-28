<form class="studip_form" action="<?= $controller->url_for('export/index'.$controller->argstring) ?>" method="POST">

    <? if ($templates): ?>
        <fieldset id="saved_templates">
            <legend><?= _("Vorlagen") ?></legend>
            <? foreach ($templates as $template): ?>
                <a class="close" href="<?= $template['export'] ?>">
                    <?= Assets::img("icons/16/blue/export/file.png") ?>
                    <?= htmlReady($template['name']) ?>
                </a>
                <a class="stay_on_dialog" href='<?= $template['delete'] ?>' ><?= Assets::img('icons/12/blue/decline.png') ?></a>
                <br />
            <? endforeach; ?> 
        </fieldset>
    <? endif; ?>

    <fieldset id="export_as">
        <legend><?= _("Exportieren als") ?></legend>
        <? foreach ($formats as $format): ?>
            <a class='close' href="<?= $exportlink[$format] ?>">
                <?= Assets::img("icons/16/blue/file-" . $format . ".png") ?>
                <?= $format ?>
            </a><br />
        <? endforeach; ?>
    </fieldset>

    <? if ($templating): ?>
        <fieldset id="create_new_template">
            <legend><?= _("Neue Vorlagen anlegen") ?></legend>
            <label>Format
                <select name="format">
                    <? foreach ($formats as $format): ?>
                        <option><?= $format ?></option>
                    <? endforeach; ?>
                </select>
            </label>
            <label><?= _('Vorlagenname') ?>
                <input type="text" name="templatename" placeholder="<?= _('Vorlagenname') ?>">
            </label>

            <fieldset>
                <legend><?= _("Anpassbare Elemente") ?></legend>
                <? foreach ($preview as $pref): ?>
                    <?= $pref ?>
                <? endforeach; ?>
            </fieldset>

            <input type="hidden" name="args" value='<?= $flash['args'] ?>'></input>
            <?= \Studip\Button::create(_("Anlegen"), 'create', array("class" => "stay_on_dialog")) ?>
        </fieldset>
    <? endif; ?>

    <? if (Request::get('plugin')): ?>
        <input type="hidden" name="plugin" value="<?= Request::get('plugin') ?>">
        <input type="hidden" name="path" value="<?= Request::get('path') ?>">
    <? endif; ?>


</form>