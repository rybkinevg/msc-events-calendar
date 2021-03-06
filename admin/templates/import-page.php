<?php

global $wpdb;

$table_name = $wpdb->prefix . 'mscec_imports';

$history = $wpdb->get_results('SELECT * FROM ' . $table_name);

if (isset($_GET['delete_history']) && $_GET['delete_history']) {

    $file = MSCEC_DIR . 'imports/' . $_GET['name'];

    wp_delete_file( $file );

    $wpdb->delete( $table_name, ['ID' => $_GET['id']] );
}

?>

<div id="events-import-page" class="wrap">
    <h1 class="wp-heading-inline">
        Импорт мероприятий
    </h1>
    <div id="message" class="notice" style="display: none;">
        <p class="message-text"></p>
    </div>

    <div class="wrapper">
        <input class="tabs-radio" id="one" name="group" type="radio" checked>
        <input class="tabs-radio" id="two" name="group" type="radio">
        <input class="tabs-radio" id="three" name="group" type="radio">
        <div class="tabs-container">
            <div class="tabs__list">
                <label class="tab" id="one-tab" for="one">Импорт</label>
                <label class="tab" id="two-tab" for="two">История</label>
            </div>
        </div>
        <div class="panels">
            <div class="panel" id="one-panel">
                <div class="panel__inner">
                    <h2 class="panel-inner-title">Загрузка файла</h2>
                    <p>Выберите файл на вашем устройстве и загрузите, после удачной загрузки будут дальнейшие иструкции.</p>
                    <div id="events-import">
                        <form id="events-import__form">
                            <input type="hidden" name="action" value="import_events_action" />
                            <input type="hidden" name="nonce" value="<?= wp_create_nonce('import_events_nonce') ?>" />
                            <div class="input-file-wrapper">
                                <input id="events-import__file" type="file" name="imported-events-file">
                                <span class="import-submit-loader has-loader disabled">
                                    <input id="events-import__submit" type="submit" class="button" value="Загрузить" disabled>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel" id="two-panel">
                <div class="panel__inner">
                    <h2 class="panel-inner-title">История импортов</h2>
                    <div class="events-insert__table-wrapper">
                        <table class="events-insert__table widefat striped">
                            <thead class="events-insert-table__head">
                                <tr class='events-insert-table__titles'>
                                    <td class="events-insert-table__title">ID</td>
                                    <td class="events-insert-table__title">Название файла</td>
                                    <td class="events-insert-table__title">Дата</td>
                                    <td class="events-insert-table__title">Время</td>
                                    <td class="events-insert-table__title">Количество мероприятий</td>
                                    <td class="events-insert-table__title">Пользователь</td>
                                    <td class="events-insert-table__title">Скачать</td>
                                    <td class="events-insert-table__title">Удалить</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if ($history) {
                                    foreach ($history as $item) {

                                        $date = date('d.m.Y', strtotime($item->date));

                                        echo "<tr>";
                                        echo "<td>{$item->id}</td>";
                                        echo "<td>{$item->name}</td>";
                                        echo "<td>{$date}</td>";
                                        echo "<td>{$item->time}</td>";
                                        echo "<td>{$item->count}</td>";
                                        echo "<td>{$item->user}</td>";
                                        echo "<td><a href='{$item->file}'>Скачать</a></td>";
                                        echo "<td><a href='?post_type=events&page=import&delete_history=true&id={$item->id}&name={$item->name}'>Удалить</a></td>";
                                        echo '</tr>';
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Не найдено</td></tr>";
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>