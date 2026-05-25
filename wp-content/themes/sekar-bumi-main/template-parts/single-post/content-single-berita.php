
<?php
$author = get_post_meta( get_the_ID(), 'penulis_cerita', true );

if( !empty( $author ) ){
    $author = sprintf(
        '<span class="cf-single-content--author">%1$s</span>',
        __( 'Oleh: ' . $author, 'sekarbumi' )
    );
}
?>
<div class="uk-section cf-single">
	<div class="uk-container">
		<div class="uk-width-1-1 uk-flex uk-flex-column uk-flex-center uk-flex-middle uk-text-center">
			<div class="cf-single-thumbnail uk-border-rounded uk-overflow-hidden">
				<?php printf( '%s', get_the_post_thumbnail() ) ?>;
			</div>
			<div class="cf-single-content">
                <h3 class="cf-single-content--title"><?php printf( '%s', get_the_title() ); ?></h3>
                <?php printf( '%s', $author ); ?>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</div>