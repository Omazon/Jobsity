<?php
get_header();
?>
<div id="main-content">
    <div class="container">
        <h3><?= esc_js(get_query_var('movie_api')); ?></h3>
    </div>
</div>


<?php
get_footer();
