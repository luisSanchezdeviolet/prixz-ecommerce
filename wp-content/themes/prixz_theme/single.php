<?php

use SimplePie\Sanitize;

if (!defined('ABSPATH')) {
    die();;
}

get_header();

if(isset($_POST['nonce'])) {
    wp_insert_comment(array(
        'comment_post_ID' => get_the_ID(),
        'comment_author' => sanitize_text_field($_POST['author']),
        'comment_author_email' => sanitize_text_field($_POST['email']),
        'comment_author_url' => 'test',
        'comment_content' => sanitize_text_field($_POST['comentarios']),
        'comment_author_ip' => '127.0.0.1',
        'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
        'comment_type' => '',
        'comment_date' => date('Y-m-d H:i:s'),
        'comment_date_gmt' => date('Y-m-d H:i:s'),
        'comment_approved' => 0
    ));

?>

<script>
    Swal.fire({
        icon: 'success',
        title: 'OK',
        text: 'Se envió tu comentario exitosamente. Será revisado para su publicación'
    });

    window.location=location.href;
</script>

<?php
}

$comentarios = get_comments('post_id='.get_the_ID() );

while (have_posts()):
    the_post();
?>

    <main class="container">
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo get_site_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo get_site_url(); ?>/blog">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>

            <div class="col-8">
                <h1><?php the_title(); ?></h1>
                <div class="card">
                    <?php the_post_thumbnail('full', array('class' => 'img-fluid img-blog')); ?>
                    <div class="card-body">
                        <p><?= the_category(' '); ?> | <?= get_the_date(); ?></p>
                        <p><?= the_content(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                sidebard
            </div>
        </div>

        <!--comentarios-->
        <div class="row">
            <section class="content-item">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <form action="" method="POST" name="form" class="comment-form">
                                <h3>Deja tu comentario</h3>
                                <p>Su dirección de correo electrónico no será publicado</p>
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-sm-9 col-lg-10">
                                            <input type="text" name="author" id="author" class="form-control" placeholder="Nombre" />
                                        </div>

                                        <div class="form-group col-xs-12 col-sm-9 col-lg-10 mt-2">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" />
                                        </div>

                                        <div class="form-group col-xs-12 col-sm-9 col-lg-10 mt-2">
                                            <textarea name="comentarios" id="comentarios" class="form-control" placeholder="Tu comentario"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <input type="hidden" name="nonce" value="<?= wp_create_nonce('seg'); ?>">

                                <button type="button" class="btn btn-primary mt-3" title="Enviar" onclick="confirmarSubmit();">Enviar</button>
                            </form>
                            <!---Listar comentarios-->
                            <h3><?= sizeof($comentarios); ?> Comentarios</h3>
                            <?php 
                                foreach($comentarios as $comentario) {
                                    ?>
                                    <div class="media">
                                        <img src="" alt="">
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

<?php
endwhile;
get_footer();
?>