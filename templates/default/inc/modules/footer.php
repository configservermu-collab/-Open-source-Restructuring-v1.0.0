<div class="footer-container">
	<div class="row">
		<div class="col-xs-12">
			<a href="<?php echo __BASE_URL__; ?>tos/"><?php echo lang('footer_terms'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>privacy/"><?php echo lang('footer_privacy'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>refunds/"><?php echo lang('footer_refund'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>info/"><?php echo lang('footer_info'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>contact/"><?php echo lang('footer_contact'); ?></a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-8">
			<p>
				<?php echo langf('footer_copyright', array(config('server_name', true), date("Y"))); ?><br />
				<?php echo lang('footer_webzen_copyright'); ?>
			</p>
			<br />
			
			<?php $handler->webenginePowered(); ?>
		</div>
		<?php
		$socialLinks = array(
			array('social_link_facebook', 'Facebook', 'fab fa-facebook-f'),
			array('social_link_instagram', 'Instagram', 'fab fa-instagram'),
			array('social_link_discord', 'Discord', 'fab fa-discord'),
			array('social_link_youtube', 'YouTube', 'fab fa-youtube'),
			array('social_link_twitter', 'Twitter / X', 'fab fa-twitter'),
			array('social_link_tiktok', 'TikTok', 'fab fa-tiktok'),
			array('social_link_whatsapp', 'WhatsApp', 'fab fa-whatsapp'),
		);
		$visibleSocialLinks = array();
		foreach($socialLinks as $socialLink) {
			$socialUrl = trim((string)config($socialLink[0], true));
			if($socialUrl !== '') {
				$visibleSocialLinks[] = array($socialUrl, $socialLink[1], $socialLink[2]);
			}
		}
		if(!empty($visibleSocialLinks)) {
			echo '<div class="col-xs-12 col-sm-4">';
			echo '<div class="footer-social-links" aria-label="Redes sociales">';
			foreach($visibleSocialLinks as $socialLink) {
				echo '<a href="'.htmlspecialchars($socialLink[0], ENT_QUOTES, 'UTF-8').'" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="'.htmlspecialchars($socialLink[1], ENT_QUOTES, 'UTF-8').'">';
				echo '<i class="'.htmlspecialchars($socialLink[2], ENT_QUOTES, 'UTF-8').'" aria-hidden="true"></i>';
				echo '</a>';
			}
			echo '</div>';
			echo '</div>';
		}
		?>
	</div>
</div>