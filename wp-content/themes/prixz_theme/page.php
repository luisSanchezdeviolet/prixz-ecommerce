<?php

if (!defined('ABSPATH')) {
    die();;
}

get_header();
?>

<main class="container">
    <?php the_content(); ?>
</main>

<?php

get_footer();

?>