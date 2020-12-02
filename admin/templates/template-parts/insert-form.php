<?php

$array = $this->imported_file['csv'];

$select_values = [
    'unset'        => 'Не импортировать',
    'post_title'   => 'Название мероприятия',
    'post_content' => 'Описание мероприятия',
    'post_type'    => 'Тип мероприятия',
    'date'         => 'Дата мероприятия',
    'time_start'   => 'Время начала мероприятия',
    'time_end'     => 'Время окончания мероприятия',
    'organizer'    => 'Организатор мероприятия',
    'address'      => 'Адрес мероприятия',
    'place'        => 'Место проведения мероприятия'
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

<div id="events-insert">
    <form id="events-insert__form">
        <input type="hidden" name="action" value="insert_events_action" />
        <input type="hidden" name="nonce" value="<?= wp_create_nonce('insert_events_nonce') ?>" />
        <div class="events-insert__table-wrapper" style="overflow-x: auto;">
            <table class="events-insert__table">
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
                            echo "<td>$key</td>";
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
        <input type="submit" value="Импортировать">
    </form>
</div>