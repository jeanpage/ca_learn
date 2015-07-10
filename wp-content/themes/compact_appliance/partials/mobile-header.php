<header id="mobile-header">
	<section class="mobile-logo">
		<a href="<?php echo home_url() ;?>">
			<img src="<?php the_field('mobile_logo', 'option');?>"/>
		</a>
	</section>
	<section class="mobile-shop-link">
		<a href="http://compactappliance.com/">shop</a>
	</section>
	<section class="mobile-cart"><a href="http://compactappliance.com/on/demandware.store/Sites-Appliance-Site/default/Cart-Show"><i class="icon-cart"></i></a></section>
</header>
<section id="mobile-nav">
	<a href="#" class="mobile-drop">
		<img src="<?php echo get_stylesheet_directory_uri();?>/images/menu-icon.png"/>
	</a>
	<section class="mobile-search"><?php echo mobile_search_form();?></section>
</section>
<section id="mobile-drop-menu">
	<div class="section-container accordion" data-section="accordion">
		<?php
		$ca_columns = _get_field( 'ca_column','option' );
		$colcount = count( $ca_columns );
		foreach ( $ca_columns as $column ) :
			$col_head = $column['column_heading'];
			$col_sub = $column['column_subhead'];
			$see_all_link = $column['see_all_link'];
			$see_all_label = $column['see_all_label']; ?>
			<section>
				<p class="title" data-section-title><?php echo $col_head; ?></p>
				<div class="content" data-section-content>
					<?php $col_links = $column['column_link'];
					foreach( $col_links as $link ) :
						if ( $link ) :
							$link_url = $link['link_url'];
							$link_text = $link['link_text']; ?>
							<a href="<?php echo $link_url;?>"><p><?php echo $link_text; ?></p></a>
							<?php
						endif;
					endforeach;
					if ( $see_all_link ) : ?>
					<a class="mobile-see-all" href="<?php echo $see_all_link; ?>"><p><?php echo $see_all_label; ?></p></a>
					<?php endif; ?>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
</section>