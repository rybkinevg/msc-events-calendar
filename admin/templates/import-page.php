<div id="events-import-page" class="wrap">
    <h1 class="wp-heading-inline">
        Импорт мероприятий
    </h1>
    <div id="message" class="notice" style="display: none;">
        <p class="message-text"></p>
    </div>
    <div id="events-import">
        <form id="events-import__form">
            <input type="hidden" name="action" value="import_events_action" />
            <input type="hidden" name="nonce" value="<?= wp_create_nonce('import_events_nonce') ?>" />
            <input id="events-import__file" type="file" name="imported-events-file">
            <input type="submit" value="Загрузить">
        </form>
    </div>

</div>