<?php

$array = $this->imported_file_array;
$url = $this->imported_file_url;

$select_values = [
    'unset'        => 'Не импортировать',
    'organizer'    => 'Организатор мероприятия',
    'openness'     => 'Открытость мероприятия',
    'type'         => 'Тип мероприятия',
    'post_title'   => 'Название мероприятия',
    'post_content' => 'Описание мероприятия',
    'date'         => 'Дата мероприятия',
    'time_start'   => 'Время начала мероприятия',
    'time_end'     => 'Время окончания мероприятия',
    'address'      => 'Адрес мероприятия',
    'place'        => 'Место проведения мероприятия',
    'platform'     => 'Платформа проведения трансляции',
    'link'         => 'Ссылка на трасляцию',
    'password'     => 'Пароль трансляции',
];

function get_select_values($values, $id)
{
    $options = '';

    foreach ($values as $key => $value) {
        $options .= "<option value='$key'>$value</option>";
    }

    return "<select id='$id' name='$id'>$options<select>";
}

?>

<div class="panel__inner">
    <h2 class="panel-inner-title">Выбор полей для импорта</h2>
    <p>Выберите правильное название каждой колонки таблицы, если колонку не нужно импортировать, выберите "Не импортировать".</p>
    <p>Необходимые для корректного импорта поля:</p>
    <ul style="list-style: circle; padding-left: 30px;">
        <li>Название меропрития (post_title)</li>
        <li>Описание меропрития (post_content)</li>
    </ul>
    <p>После выбора нажмите кнопку "Импортировать", которая находится под таблицей, чтобы отменить импорт нажмите кнопку "Отменить", находится тоже под таблицей.</p>
    <div id="events-insert">
        <form id="events-insert__form">
            <input type="hidden" name="action" value="insert_events_action" />
            <input type="hidden" name="file" value="<?= $url ?>" />
            <input type="hidden" name="nonce" value="<?= wp_create_nonce('insert_events_nonce') ?>" />
            <div class="events-insert__table-wrapper">
                <table class="events-insert__table widefat striped">
                    <thead class="events-insert-table__head">
                        <?php

                        for ($i = 0; $i < 1; $i++) {

                            $item = $array[$i];

                            echo "<tr class='events-insert-table__select'>";

                            foreach ($item as $key => $value) {
                                echo '<td>' . get_select_values($select_values, $key) . '</td>';
                            }

                            echo "</tr>";
                        }

                        for ($i = 0; $i < 1; $i++) {

                            $item = $array[$i];

                            echo "<tr class='events-insert-table__titles'>";

                            foreach ($item as $key => $value) {
                                echo "<td class='events-insert-table__title'>$key</td>";
                            }

                            echo "</tr>";
                        }

                        ?>
                    </thead>
                    <tbody class="events-insert-table__body">
                        <?php

                        for ($i = 0; $i < count($array); $i++) {

                            $item = $array[$i];

                            echo "<tr>";

                            foreach ($item as $key => $value) {
                                echo "<td>$value</td>";
                            }

                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
            <div class="events-insert__buttons">
                <span class="insert-submit-loader has-loader disabled">
                    <input id="events-insert__submit" type="submit" class="button" value="Импортировать">
                </span>
                <a href="/wp-admin/edit.php?post_type=events&page=import" class="button">Отменить</a>
            </div>
        </form>
    </div>
</div>