<?php
$amount = get_field('amount')?:10;
$sort_bar = get_field('sort_bar')?:false;
$sort_by_name = get_field('sort_by_name')?:false;
?>
<section id="upcoming-movies" class="container" data-amount="<?= $amount; ?>" data-sort-bar="<?= json_encode($sort_bar); ?>" data-sort-by-name="<?= json_encode($sort_by_name); ?>">
</section>