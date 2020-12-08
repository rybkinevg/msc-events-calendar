<?php get_header();

echo basename('http://msc-events-test/wp-content/uploads/2020/12/342-8.csv', '.csv');
echo date("H:i:s");

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <!-- Цикл WordPress -->
        <p>Выводим данные записи. Здесь работают функции для цикла, например, the_title() </p>
        <h2><?php the_title() ?></h2>
        <?php

        $metas = get_post_meta($post->ID);

        echo '<pre>';
        var_dump($metas);
        echo '</pre>';

        ?>
    <?php endwhile;
else : ?>
    <p>Записей нет.</p>
<?php endif; ?>

<?php get_footer(); ?>