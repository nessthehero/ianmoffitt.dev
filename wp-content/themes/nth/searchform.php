<!-- search -->
<form role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
    <div>
        <label class="visuallyhidden" for="s">Search for:</label>
        <input type="text" value="" name="s" id="s" placeholder="To search, type and hit enter.">
        <input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'html5blank' ); ?>">
    </div>
</form>
<!-- /search -->