<div id="headerMiddle">
	<div id="logo">
		<a href="http://www.compactappliance.com/" title="Jump to our Homepage">
			<img src="<?php the_field('main_logo', 'option');?>"/>
		</a>
	</div>
	<div class="header-special">
		<a href="#" class="header-pop" data-reveal-id="HeaderModal" data-reveal>
			<img src="<?php the_field('header_special_image', 'option');?>"/>
		</a>
	</div>
	<div class="headerSearchBox">
		<div class="headerContact">
			<div class="contactHeader">
				<a href="http://www.compactappliance.com/on/demandware.store/Sites-Appliance-Site/default/Page-Show?gcid=BRAND_CS" alt="Contact Our Product Experts">
					<b>Product Experts</b>
				</a> - Available 24 x 7
			</div><!-- contactHeader -->
			<span class="phoneFlag">&nbsp;</span>
			<span class="phoneNumber">1-800-297-6076</span> or
			<div id="lpButtonDiv1" style="display: inline; visibility: visible;">
				<a href="http://www.compactappliance.com/BRAND_CS.html">Email</a>
			</div>
		</div><!-- headerContact -->
		<div class="inputContainer">
			<form name="search_form" action="<?php the_field('search_query_url', 'option'); ?>" method="get" role="search" id="SimpleSearchForm">
				<fieldset>
					<input id="searchinput" class="headerInput" type="text" name="q" value="Search" onfocus="CngFont(this,&#39;fontStyle&#39;,&#39;normal&#39;);CngFont(this,&#39;color&#39;,&#39;#000&#39;);if(this.value == &#39;Search&#39;) {this.value = &#39;&#39;;}" onblur="if (this.value == &#39;&#39;) {CngFont(this,&#39;fontStyle&#39;,&#39;italic&#39;);CngFont(this,&#39;color&#39;,&#39;#999&#39;);this.value = &#39;Search&#39;;} else if (this.value != &#39;Search&#39;) {CngFont(this,&#39;color&#39;,&#39;#000&#39;);}" autocomplete="off">
					<input class="goButton" value="Go" type="submit" name="Submit">
				</fieldset>
			</form>
		</div><!-- inputContainer -->
</div>
</div>