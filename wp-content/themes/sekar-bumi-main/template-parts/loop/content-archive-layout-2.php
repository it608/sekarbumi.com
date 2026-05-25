
<?php
	$article_link	= get_post_meta( get_the_ID(), 'article_link', true );
?>
	<div>
		<div class="uk-box-shadow-medium uk-border-rounded uk-overflow-hidden uk-text-center cf-laporan-card" data-uk-scrollspy="cls:uk-animation-slide-bottom">
			<span class="cf-small-text"><?php printf( '%s', __( ''. $args['template_name'] .' Sekarbumi Indonesia', 'sekarbumi' ) ); ?></span>
			<h1 class="cf-post-title uk-margin-bottom"><?php the_title(); ?></h1>
			<a href="<?php echo !empty( $article_link ) ? esc_url( $article_link ) : ''; ?>" target="_blank" class="cf-btn-block uk-border-rounded"><?php printf( '%s', __( 'Download', 'sekarbumi' ) ); ?></a>
		</div>
	</div>