<?php
$amount = get_field('amount')?:10;
$data_sort_bar = get_field('sort_bar')?:false;
$data_sort_by_name = get_field('sort_by_name')?:false;
?>
<section id="popular-actors" class="container mx-auto" data-amount="<?= $amount; ?>" data-sort-bar="<?= json_encode($data_sort_bar); ?>" data-sort-by-name="<?= json_encode($data_sort_by_name); ?>">
</section>
