<!-- search -->
<form role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
    <fieldset>
        <label class="visuallyhidden" for="s">Search for:</label>
        <input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'html5blank' ); ?>">
        <div class="holder">
        	<input type="text" value="" name="s" id="s" placeholder="Search">
        </div>
    </fieldset>
</form>
<!-- /search -->
