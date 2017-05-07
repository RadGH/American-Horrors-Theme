<form role="search" method="GET" class="search-form" action="<?php echo esc_attr( site_url() ); ?>">
	<input type="text" name="s" value="<?php if(!empty($_REQUEST['s'])) echo esc_attr(stripslashes($_REQUEST['s'])); ?>" placeholder="Search this website" class="search-text" /><input type="submit" value="Search" class="search-submit button" />
</form>