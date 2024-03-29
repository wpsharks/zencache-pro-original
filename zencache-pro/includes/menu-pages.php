<?php
namespace zencache // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	class menu_pages // Plugin options.
	{
		protected $plugin; // Set by constructor.

		public function __construct()
		{
			$this->plugin = plugin();
		}

		public function options()
		{
			echo '<form id="plugin-menu-page" class="plugin-menu-page" method="post" enctype="multipart/form-data"'.
			     ' action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce())), self_admin_url('/admin.php'))).'">'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-heading">'."\n";

			/* ----------------------------------------------------------------------------------------- */

			if(is_multisite()) // Wipes entire cache (e.g. this clears ALL sites in a network).
				echo '   <button type="button" class="plugin-menu-page-wipe-cache" style="float:right; margin-left:15px;" title="'.esc_attr(__('Wipe Cache (Start Fresh); clears the cache for all sites in this network at once!', $this->plugin->text_domain)).'"'.
				     '      data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), __NAMESPACE__ => array('wipe_cache' => '1'))), self_admin_url('/admin.php'))).'">'.
				     '      '.__('Wipe', $this->plugin->text_domain).' <img src="'.esc_attr($this->plugin->url('/client-s/images/wipe.png')).'" style="width:16px; height:16px;" /></button>'."\n";

			echo '   <button type="button" class="plugin-menu-page-clear-cache" style="float:right;" title="'.esc_attr(__('Clear Cache (Start Fresh)', $this->plugin->text_domain).((is_multisite()) ? __('; affects the current site only.', $this->plugin->text_domain) : '')).'"'.
			     '      data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), __NAMESPACE__ => array('clear_cache' => '1'))), self_admin_url('/admin.php'))).'">'.
			     '      '.__('Clear', $this->plugin->text_domain).' <img src="'.esc_attr($this->plugin->url('/client-s/images/clear.png')).'" style="width:16px; height:16px;" /></button>'."\n";

			echo '   <button type="button" class="plugin-menu-page-restore-defaults"'. // Restores default options.
			     '      data-confirmation="'.esc_attr(__('Restore default plugin options? You will lose all of your current settings! Are you absolutely sure about this?', $this->plugin->text_domain)).'"'.
			     '      data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), __NAMESPACE__ => array('restore_default_options' => '1'))), self_admin_url('/admin.php'))).'">'.
			     '      '.__('Restore', $this->plugin->text_domain).' <i class="fa fa-ambulance"></i></button>'."\n";

			echo '   <div class="plugin-menu-page-panel-togglers" title="'.esc_attr(__('All Panels', $this->plugin->text_domain)).'">'."\n";
			echo '      <button type="button" class="plugin-menu-page-panels-open"><i class="fa fa-chevron-down"></i></button>'."\n";
			echo '      <button type="button" class="plugin-menu-page-panels-close"><i class="fa fa-chevron-up"></i></button>'."\n";
			echo '   </div>'."\n";

			echo '   <div class="plugin-menu-page-upsells">'."\n";
			if(current_user_can($this->plugin->update_cap)) echo '<a href="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-pro-updater')), self_admin_url('/admin.php'))).'"><i class="fa fa-magic"></i> '.__('Pro Updater', $this->plugin->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://zencache.com/r/zencache-subscribe/').'" target="_blank"><i class="fa fa-envelope"></i> '.__('Newsletter (Subscribe)', $this->plugin->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://zencache.com/r/zencache-beta-testers-list/').'" target="_blank"><i class="fa fa-envelope"></i> '.__('Beta Testers (Signup)', $this->plugin->text_domain).'</a>'."\n";
			echo '   </div>'."\n";

			echo '   <img src="'.$this->plugin->url('/client-s/images/options.png').'" alt="'.esc_attr(__('Plugin Options', $this->plugin->text_domain)).'" />'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<hr />'."\n";

			/* ----------------------------------------------------------------------------------------- */

			if(!empty($_REQUEST[__NAMESPACE__.'__updated'])) // Options updated successfully?
			{
				echo '<div class="plugin-menu-page-notice notice">'."\n";
				echo '   <i class="fa fa-thumbs-up"></i> '.__('Options updated successfully.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__restored'])) // Restored default options?
			{
				echo '<div class="plugin-menu-page-notice notice">'."\n";
				echo '   <i class="fa fa-thumbs-up"></i> '.__('Default options successfully restored.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__cache_wiped']))
			{
				echo '<div class="plugin-menu-page-notice notice">'."\n";
				echo '   <img src="'.esc_attr($this->plugin->url('/client-s/images/wipe.png')).'" /> '.__('Cache wiped across all sites; recreation will occur automatically over time.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__cache_cleared']))
			{
				echo '<div class="plugin-menu-page-notice notice">'."\n";
				echo '   <img src="'.esc_attr($this->plugin->url('/client-s/images/clear.png')).'" /> '.__('Cache cleared for this site; recreation will occur automatically over time.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__wp_config_wp_cache_add_failure']))
			{
				echo '<div class="plugin-menu-page-notice error">'."\n";
				echo '   <i class="fa fa-thumbs-down"></i> '.__('Failed to update your <code>/wp-config.php</code> file automatically. Please add the following line to your <code>/wp-config.php</code> file (right after the opening <code>&lt;?php</code> tag; on it\'s own line). <pre class="code"><code>&lt;?php<br />define(\'WP_CACHE\', TRUE);</code></pre>', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__wp_config_wp_cache_remove_failure']))
			{
				echo '<div class="plugin-menu-page-notice error">'."\n";
				echo '   <i class="fa fa-thumbs-down"></i> '.__('Failed to update your <code>/wp-config.php</code> file automatically. Please remove the following line from your <code>/wp-config.php</code> file, or set <code>WP_CACHE</code> to a <code>FALSE</code> value. <pre class="code"><code>define(\'WP_CACHE\', TRUE);</code></pre>', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__advanced_cache_add_failure']))
			{
				echo '<div class="plugin-menu-page-notice error">'."\n";
				if($_REQUEST[__NAMESPACE__.'__advanced_cache_add_failure'] === 'zc-advanced-cache')
					echo '   <i class="fa fa-thumbs-down"></i> '.sprintf(__('Failed to update your <code>/wp-content/advanced-cache.php</code> file. Cannot write stat file: <code>%1$s/zc-advanced-cache</code>. Please be sure this directory exists (and that it\'s writable): <code>%1$s</code>. Please use directory permissions <code>755</code> or higher (perhaps <code>777</code>). Once you\'ve done this, please try again.', $this->plugin->text_domain), esc_html($this->plugin->cache_dir()))."\n";
				else echo '   <i class="fa fa-thumbs-down"></i> '.__('Failed to update your <code>/wp-content/advanced-cache.php</code> file. Most likely a permissions error. Please create an empty file here: <code>/wp-content/advanced-cache.php</code> (just an empty PHP file, with nothing in it); give it permissions <code>644</code> or higher (perhaps <code>666</code>). Once you\'ve done this, please try again.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__advanced_cache_remove_failure']))
			{
				echo '<div class="plugin-menu-page-notice error">'."\n";
				echo '   <i class="fa fa-thumbs-down"></i> '.__('Failed to remove your <code>/wp-content/advanced-cache.php</code> file. Most likely a permissions error. Please delete (or empty the contents of) this file: <code>/wp-content/advanced-cache.php</code>.', $this->plugin->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!$this->plugin->options['enable']) // Not enabled yet?
			{
				echo '<div class="plugin-menu-page-notice warning">'."\n";
				echo '   <i class="fa fa-warning"></i> '.sprintf(__('%1$s is currently disabled; please review options below.', $this->plugin->text_domain), esc_html($this->plugin->name))."\n";
				echo '</div>'."\n";
			}
			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-body">'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '         <h2 class="plugin-menu-page-section-heading">'.
			     '            '.__('Basic Configuration (Required)', $this->plugin->text_domain).
			     '            <small><span>'.sprintf(__('Review these basic options and %1$s&trade; will be ready-to-go!', $this->plugin->text_domain), esc_html($this->plugin->name)).'</span></small>'.
			     '         </h2>';

			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading'.((!$this->plugin->options['enable']) ? ' open' : '').'">'."\n";
			echo '      <i class="fa fa-flag"></i> '.__('Enable/Disable', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body'.((!$this->plugin->options['enable']) ? ' open' : '').' clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/tach.png')).'" style="float:right; width:100px; margin-left:1em;" />'."\n";
			echo '      <p style="float:right; font-size:120%; font-weight:bold;">'.sprintf(__('%1$s&trade; = SPEED<em>!!</em>', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><label class="switch-primary"><input type="radio" name="'.esc_attr(__NAMESPACE__).'[save_options][enable]" value="1"'.checked($this->plugin->options['enable'], '1', FALSE).' /> '.sprintf(__('Yes, enable %1$s&trade;', $this->plugin->text_domain), esc_html($this->plugin->name)).' <i class="fa fa-magic fa-flip-horizontal"></i></label> &nbsp;&nbsp;&nbsp; <label><input type="radio" name="'.esc_attr(__NAMESPACE__).'[save_options][enable]" value="0"'.checked($this->plugin->options['enable'], '0', FALSE).' /> '.__('No, disable.', $this->plugin->text_domain).'</label></p>'."\n";
			echo '      <p class="info" style="font-family:\'Georgia\', serif; font-size:110%; margin-top:1.5em;">'.sprintf(__('<strong>HUGE Time-Saver:</strong> Approx. 95%% of all WordPress sites running %1$s, simply enable it here; and that\'s it :-) <strong>No further configuration is necessary (really).</strong> All of the other options (down below) are already tuned for the BEST performance on a typical WordPress installation. Simply enable %1$s here and click "Save All Changes". If you get any warnings please follow the instructions given. Otherwise, you\'re good <i class="fa fa-smile-o"></i>. This plugin is designed to run just fine like it is. Take it for a spin right away; you can always fine-tune things later if you deem necessary.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/source-code-ss.png')).'" class="screenshot" />'."\n";
			echo '      <h3>'.sprintf(__('How Can I Tell %1$s is Working?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p>'.sprintf(__('First of all, please make sure that you\'ve enabled %1$s here; then scroll down to the bottom of this page and click "Save All Changes". All of the other options (below) are already pre-configured for typical usage. Feel free to skip them all for now. You can go back through all of these later and fine-tune things the way you like them.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.sprintf(__('Once %1$s has been enabled, <strong>you\'ll need to log out (and/or clear browser cookies)</strong>. By default, cache files are NOT served to visitors who are logged-in, and that includes you too ;-) Cache files are NOT served to recent comment authors either. If you\'ve commented (or replied to a comment lately); please clear your browser cookies before testing.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.sprintf(__('<strong>To verify that %1$s is working</strong>, navigate your site like a normal visitor would. Right-click on any page (choose View Source), then scroll to the very bottom of the document. At the bottom, you\'ll find comments that show %1$s stats and information. You should also notice that page-to-page navigation is <i class="fa fa-flash"></i> <strong>lightning fast</strong> now that %1$s is running; and it gets faster over time!', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][debugging_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['debugging_enable'], '1', FALSE).'>'.__('Yes, enable notes in the source code so I can see it\'s working (recommended).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="2"'.selected($this->plugin->options['debugging_enable'], '2', FALSE).'>'.__('Yes, enable notes in the source code AND show debugging details (not recommended for production).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['debugging_enable'], '0', FALSE).'>'.__('No, I don\'t want my source code to contain any of these notes.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-shield"></i> '.__('Plugin Deletion Safeguards', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-shield fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Uninstall on Plugin Deletion; or Safeguard Options?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('<strong>Tip:</strong> By default, if you delete %1$s using the plugins menu in WordPress, nothing is lost. However, if you want to completely uninstall %1$s you should set this to <code>Yes</code> and <strong>THEN</strong> deactivate &amp; delete %1$s from the plugins menu in WordPress. This way %1$s will erase your options for the plugin, erase directories/files created by the plugin, remove the <code>advanced-cache.php</code> file, terminate CRON jobs, etc. It erases itself from existence completely.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][uninstall_on_deletion]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['uninstall_on_deletion'], '0', FALSE).'>'.__('Safeguard my options and the cache (recommended).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['uninstall_on_deletion'], '1', FALSE).'>'.sprintf(__('Yes, uninstall (completely erase) %1$s on plugin deletion.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '         <h2 class="plugin-menu-page-section-heading">'.
			     '            '.__('Advanced Configuration (All Optional)', $this->plugin->text_domain).
			     '            <small>'.__('Recommended for advanced site owners only; already pre-configured for most WP installs.', $this->plugin->text_domain).'</small>'.
			     '         </h2>';

			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-info-circle"></i> '.__('Clearing the Cache', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <h2 style="margin-top:0; font-weight:bold;">'.__('Clearing the Cache Manually', $this->plugin->text_domain).'</h2>'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/clear-cache-ss.png')).'" class="screenshot" />'."\n";
			echo '      <p>'.sprintf(__('Once %1$s is enabled, you will find this new option in your WordPress Admin Bar (see screenshot on right). Clicking this button will clear the cache and you can start fresh at anytime (e.g. you can do this manually; and as often as you wish).', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.sprintf(__('Depending on the structure of your site, there could be many reasons to clear the cache. However, the most common reasons are related to Post/Page edits or deletions, Category/Tag edits or deletions, and Theme changes. %1$s handles most scenarios all by itself. However, many site owners like to clear the cache manually; for a variety of reasons (just to force a refresh).', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][admin_bar_enable]" style="width:auto;">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['admin_bar_enable'], '1', FALSE).'>'.__('Yes, enable the &quot;Clear Cache&quot; button in the WordPress admin bar.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['admin_bar_enable'], '0', FALSE).'>'.__('No, I don\'t intend to clear the cache manually; exclude from admin bar.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Running the <a href="http://www.websharks-inc.com/product/s2clean/" target="_blank">s2Clean Theme</a> by WebSharks?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('If s2Clean is installed, %1$s can be configured to clear the Markdown cache too (if you\'ve enabled Markdown processing with s2Clean). The s2Clean Markdown cache is only cleared when you manually clear the cache (with %1$s); and only if you enable this option here. Note: s2Clean\'s Markdown cache is extremely dynamic. Just like the rest of your site, s2Clean caches do NOT need to be cleared away at all, as this happens automatically when your content changes. However, some developers find this feature useful while developing their site; just to force a refresh.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_s2clean_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_s2clean_enable'], '1', FALSE).'>'.__('Yes, if the s2Clean theme is installed; also clear s2Clean-related caches.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_s2clean_enable'], '0', FALSE).'>'.__('No, I don\'t use s2Clean; or, I don\'t want s2Clean-related caches cleared.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Process Other Custom PHP Code?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('If you have other custom routines you\'d like to process when the cache is cleared manually, please type your custom PHP code here. The PHP code that you provide is only evaluated when you manually clear the cache (with %1$s); and only if the field below contains PHP code. Note: if your PHP code outputs a message (e.g. if you have <code>echo \'&lt;p&gt;My message&lt;/p&gt;\';</code>); your message will be displayed along with any other notes from %1$s itself. This could be useful to developers that need to clear server caches too (such as <a href="http://www.php.net/manual/en/function.apc-clear-cache.php" target="_blank">APC</a> or <a href="http://www.php.net/manual/en/memcache.flush.php" target="_blank">memcache</a>).', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p style="margin-bottom:0;"><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_eval_code]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cache_clear_eval_code']).'</textarea></p>'."\n";
			echo '      <p class="info" style="margin-top:0;">'.__('<strong>Example:</strong> <code>&lt;?php apc_clear_cache(); echo \'&lt;p&gt;Also cleared APC cache.&lt;/p&gt;\'; ?&gt;</code>', $this->plugin->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h2 style="font-weight:bold;">'.__('Clearing the Cache Automatically', $this->plugin->text_domain).'</h2>'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/auto-clear-ss.png')).'" class="screenshot" />'."\n";
			echo '      <p>'.sprintf(__('This is built into the %1$s plugin; e.g. this functionality is "always on". If you edit a Post/Page (or delete one), %1$s will automatically clear the cache file(s) associated with that content. This way a new updated version of the cache will be created automatically the next time this content is accessed. Simple updates like this occur each time you make changes in the Dashboard, and %1$s will notify you of these as they occur. %1$s monitors changes to Posts (of any kind, including Pages), Categories, Tags, Links, Themes (even Users); and more. Notifications in the Dashboard regarding these detections can be enabled/disabled below.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][change_notifications_enable]" style="width:auto;">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['change_notifications_enable'], '1', FALSE).'>'.sprintf(__('Yes, enable %1$s notifications in the Dashboard when changes are detected &amp; one or more cache files are cleared automatically.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['change_notifications_enable'], '0', FALSE).'>'.sprintf(__('No, I don\'t want to know (don\'t really care) what %1$s is doing behind-the-scene.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Auto-Clear Designated "Home Page" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('On many sites, the Home Page (aka: the Front Page) offers an archive view of all Posts (or even Pages). Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the "Home Page"?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_home_page_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_home_page_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Home Page".', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_home_page_enable'], '0', FALSE).'>'.__('No, my Home Page does not provide a list of Posts/Pages; e.g. this is not necessary.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Auto-Clear Designated "Posts Page" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('On many sites, the Posts Page (aka: the Blog Page) offers an archive view of all Posts (or even Pages). Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the "Posts Page"?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_posts_page_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_posts_page_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Posts Page".', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_posts_page_enable'], '0', FALSE).'>'.__('No, I don\'t use a separate Posts Page; e.g. my Home Page IS my Posts Page.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Auto-Clear "Author Page" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('On many sites, each author has a related "Author Page" that offers an archive view of all posts associated with that author. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the related "Author Page"?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_author_page_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_author_page_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the "Author Page".', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_author_page_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use multiple authors and/or I don\'t have any "Author Page" archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Auto-Clear "Category Archives" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('On many sites, each post is associated with at least one Category. Each category then has an archive view that contains all the posts within that category. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Category archive views?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_term_category_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_category_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the associated Category archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_category_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use Categories and/or I don\'t have any Category archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Auto-Clear "Tag Archives" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('On many sites, each post may be associated with at least one Tag. Each tag then has an archive view that contains all the posts assigned that tag. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Tag archive views?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_term_post_tag_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_post_tag_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the associated Tag archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_post_tag_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use Tags and/or I don\'t have any Tag archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Auto-Clear "Custom Term Archives" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('Most sites do not use any custom Terms so it should be safe to leave this disabled. However, if your site uses custom Terms and they have their own Term archive views, you may want to clear those when the associated post is cleared. Therefore, if a single Post/Page is changed in some way; and %1$s clears/resets the cache for a single Post/Page, would you like %1$s to also clear any existing cache files for the associated Tag archive views?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_term_other_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_term_other_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear any associated custom Term archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_term_other_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use any custom Terms and/or I don\'t have any custom Term archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <h3>'.__('Auto-Clear "Custom Post Type Archives" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('Most sites do not use any Custom Post Types so it should be safe to disable this option. However, if your site uses Custom Post Types and they have their own Custom Post Type archive views, you may want to clear those when any associated post is cleared. Therefore, if a single Post with a Custom Post Type is changed in some way; and %1$s clears/resets the cache for that post, would you like %1$s to also clear any existing cache files for the associated Custom Post Type archive views?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_custom_post_type_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_custom_post_type_enable'], '1', FALSE).'>'.__('Yes, if any single Post with a Custom Post Type is cleared/reset; also clear any associated Custom Post Type archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_custom_post_type_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use any Custom Post Types and/or I don\'t have any Custom Post Type archive views.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Auto-Clear "RSS/RDF/ATOM Feeds" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('If you enable Feed Caching (below), this can be quite handy. If enabled, when you update a Post/Page, approve a Comment, or make other changes where %1$s can detect that certain types of Feeds should be cleared to keep your site up-to-date, then %1$s will do this for you automatically. For instance, the blog\'s master feed, the blog\'s master comments feed, feeds associated with comments on a Post/Page, term-related feeds (including mixed term-related feeds), author-related feeds, etc. Under various circumstances (i.e. as you work in the Dashboard) these can be cleared automatically to keep your site up-to-date.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_xml_feeds_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_xml_feeds_enable'], '1', FALSE).'>'.__('Yes, automatically clear RSS/RDF/ATOM Feeds from the cache when certain changes occur.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_xml_feeds_enable'], '0', FALSE).'>'.__('No, I don\'t have Feed Caching enabled, or I prefer not to automatically clear Feeds.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Auto-Clear "XML Sitemaps" Too?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('If you\'re generating XML Sitemaps with a plugin like <a href="http://wordpress.org/plugins/google-sitemap-generator/" target="_blank">Google XML Sitemaps</a>, you can tell %1$s to automatically clear the cache of any XML Sitemaps whenever it clears a Post/Page. Note; this does NOT clear the XML Sitemap itself of course, only the cache. The point being, to clear the cache and allow changes to a Post/Page to be reflected by a fresh copy of your XML Sitemap; sooner rather than later.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_xml_sitemaps_enable]">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_clear_xml_sitemaps_enable'], '1', FALSE).'>'.__('Yes, if any single Post/Page is cleared/reset; also clear the cache for any XML Sitemaps.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_clear_xml_sitemaps_enable'], '0', FALSE).'>'.__('No, my site doesn\'t use any XML Sitemaps and/or I prefer NOT to clear the cache for XML Sitemaps.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p><i class="fa fa-level-up fa-rotate-90"></i>&nbsp;&nbsp;&nbsp;'.__('<strong style="font-size:110%;">XML Sitemap Patterns...</strong> A default value of <code>/sitemap*.xml</code> covers all XML Sitemaps for most installations. However, you may customize this further if you deem necessary. One pattern per line please. A wildcard <code>*</code> matches zero or more characters. Searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank">REQUEST_URI</a>; e.g. a request for <code>/sitemap.xml</code> and/or <code>/sitemap-xyz.xml</code> are both matched by the pattern: <code>/sitemap*.xml</code>. If your XML Sitemap was located inside a sub-directory; e.g. <code>/my/sitemaps/xyz.xml</code>; you might add the following pattern on a new line: <code>/my/sitemaps/*.xml</code>', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][cache_clear_xml_sitemap_patterns]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cache_clear_xml_sitemap_patterns']).'</textarea></p>'."\n";
			if(is_multisite()) echo '<p class="info" style="display:block; margin-top:-15px;">'.__('In a Multisite Network, each child blog (whether it be a sub-domain, a sub-directory, or a mapped domain); will automatically change the leading <code>http://[sub.]domain/[sub-directory]</code> used in pattern matching. In short, there is no need to add sub-domains or sub-directories for each child blog in these patterns. Please include only the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank">REQUEST_URI</a> (i.e. the path) which leads to the XML Sitemap on all child blogs in the network.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Directory / Expiration Time', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <h3>'.__('Base Cache Directory (Must be Writable; e.g. <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">Permissions</a> <code>755</code> or Higher)', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('This is where %1$s will store the cached version of your site. If you\'re not sure how to deal with directory permissions, don\'t worry too much about this. If there is a problem, %1$s will let you know about it. By default, this directory is created by %1$s and the permissions are setup automatically. In most cases there is nothing more you need to do.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">'.esc_html(WP_CONTENT_DIR).'/</td><td><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][base_dir]" value="'.esc_attr($this->plugin->options['base_dir']).'" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;">/</td></tr></table>'."\n";
			echo '      <hr />'."\n";
			echo '      <i class="fa fa-clock-o fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Automatic Expiration Time (Max Age)', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If you don\'t update your site much, you could set this to <code>6 months</code> and optimize everything even further. The longer the Cache Expiration Time is, the greater your performance gain. Alternatively, the shorter the Expiration Time, the fresher everything will remain on your site. A default value of <code>7 days</code> (recommended); is a good conservative middle-ground.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.sprintf(__('Keep in mind that your Expiration Time is only one part of the big picture. %1$s will also clear the cache automatically as changes are made to the site (i.e. you edit a post, someone comments on a post, you change your theme, you add a new navigation menu item, etc., etc.). Thus, your Expiration Time is really just a fallback; e.g. the maximum amount of time that a cache file could ever possibly live.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.sprintf(__('All of that being said, you could set this to just <code>60 seconds</code> and you would still see huge differences in speed and performance. If you\'re just starting out with %1$s (perhaps a bit nervous about old cache files being served to your visitors); you could set this to something like <code>30 minutes</code>, and experiment with it while you build confidence in %1$s. It\'s not necessary to do so, but many site owners have reported this makes them feel like they\'re more-in-control when the cache has a short expiration time. All-in-all, it\'s a matter of preference <i class="fa fa-smile-o"></i>.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][cache_max_age]" value="'.esc_attr($this->plugin->options['cache_max_age']).'" /></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> the value that you specify here MUST be compatible with PHP\'s <a href="http://php.net/manual/en/function.strtotime.php" target="_blank" style="text-decoration:none;"><code>strtotime()</code></a> function. Examples: <code>30 seconds</code>, <code>2 hours</code>, <code>7 days</code>, <code>6 months</code>, <code>1 year</code>.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Note:</strong> %1$s will never serve a cache file that is older than what you specify here (even if one exists in your cache directory; stale cache files are never used). In addition, a WP Cron job will automatically cleanup your cache directory (once daily); purging expired cache files periodically. This prevents a HUGE cache from building up over time, creating a potential storage issue.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Client-Side Cache', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-desktop fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Allow Double-Caching In The Client-Side Browser?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Recommended setting: <code>No</code> (for membership sites, very important). Otherwise, <code>Yes</code> would be better (if users do NOT log in/out of your site).', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.sprintf(__('%1$s handles content delivery through its ability to communicate with a browser using PHP. If you allow a browser to (cache) the caching system itself, you are momentarily losing some control; and this can have a negative impact on users that see more than one version of your site; e.g. one version while logged-in, and another while NOT logged-in. For instance, a user may log out of your site, but upon logging out they report seeing pages on the site which indicate they are STILL logged in (even though they\'re not — that\'s bad). This can happen if you allow a client-side cache, because their browser may cache web pages they visited while logged into your site which persist even after logging out. Sending no-cache headers will work to prevent this issue.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.__('All of that being said, if all you care about is blazing fast speed and users don\'t log in/out of your site (only you do); you can safely set this to <code>Yes</code> (recommended in this case). Allowing a client-side browser cache will improve speed and reduce outgoing bandwidth when this option is feasible.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][allow_browser_cache]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['allow_browser_cache'], '0', FALSE).'>'.__('No, prevent a client-side browser cache (safest option).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['allow_browser_cache'], '1', FALSE).'>'.__('Yes, I will allow a client-side browser cache of pages on the site.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> Setting this to <code>No</code> is highly recommended when running a membership plugin like <a href="http://wordpress.org/plugins/s2member/" target="_blank">s2Member</a> (as one example). In fact, many plugins like s2Member will send <a href="http://codex.wordpress.org/Function_Reference/nocache_headers" target="_blank">nocache_headers()</a> on their own, so your configuration here will likely be overwritten when you run such plugins (which is better anyway). In short, if you run a membership plugin, you should NOT allow a client-side browser cache.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> Setting this to <code>No</code> will NOT impact static content; e.g. CSS, JS, images, or other media. This setting pertains only to dynamic PHP scripts which produce content generated by WordPress.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Advanced Tip:</strong> if you have this set to <code>No</code>, but you DO want to allow a few special URLs to be cached by the browser; you can add this parameter to your URL <code>?zcABC=1</code>. This tells %1$s that it\'s OK for the browser to cache that particular URL. In other words, the <code>zcABC=1</code> parameter tells %1$s NOT to send no-cache headers to the browser.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Logged-In Users', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-group fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Caching Enabled for Logged-In Users &amp; Comment Authors?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This should almost ALWAYS be set to <code>No</code>. Most sites will NOT want to cache content generated while a user is logged-in. Doing so could result in a cache of dynamic content generated specifically for a particular user, where the content being cached may contain details that pertain only to the user that was logged-in when the cache was generated. Imagine visiting a website that says you\'re logged-in as Billy Bob (but you\'re not Billy Bob; NOT good). In short, do NOT turn this on unless you know what you\'re doing.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <i class="fa fa-sitemap fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <p>'.sprintf(__('<strong>Exception (Membership Sites):</strong> If you run a site with many users and the majority of your traffic comes from users who ARE logged-in, please choose: <code>Yes (maintain separate cache)</code>. %1$s will operate normally; but when a user is logged-in, the cache is user-specific. %1$s will intelligently refresh the cache when/if a user submits a form on your site with the GET or POST method. Or, if you make changes to their account (or another plugin makes changes to their account); including user <a href="http://codex.wordpress.org/Function_Reference/update_user_option" target="_blank">option</a>|<a href="http://codex.wordpress.org/Function_Reference/update_user_meta" target="_blank">meta</a> additions, updates &amp; deletions too. However, please note that enabling this feature (e.g. user-specific cache entries); will eat up MUCH more disk space. That being said, the benefits of this feature for most sites will outweigh the disk overhead (e.g. it\'s NOT an issue in most cases). Unless you are short on disk space (or you have MANY thousands of users), the disk overhead is neglible.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][when_logged_in]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['when_logged_in'], '0', FALSE).'>'.__('No, do NOT cache; or serve a cache file when a user is logged-in (safest option).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="postload"'.selected($this->plugin->options['when_logged_in'], 'postload', FALSE).'>'.__('Yes, and maintain a separate cache for each user (recommended for membership sites).', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['when_logged_in'], '1', FALSE).'>'.__('Yes, but DON\'T maintain a separate cache for each user (I know what I\'m doing).', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> For most sites, the majority of their traffic (if not all of their traffic) comes from visitors who are not logged in, so disabling the cache for logged-in users is NOT ordinarily a performance issue. When a user IS logged-in, disabling the cache is considered ideal, because a logged-in user has a session open with your site; and the content they view should remain very dynamic in this scenario.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Note:</strong> This setting includes some users who AREN\'T actually logged into the system, but who HAVE authored comments recently. %1$s includes comment authors as part of it\'s logged-in user check. This way comment authors will be able to see updates to the comment thread immediately; and, so that any dynamically-generated messages displayed by your theme will work as intended. In short, %1$s thinks of a comment author as a logged-in user, even though technically they are not. ~ Users who gain access to password-protected Posts/Pages are also included.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('GET Requests', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Caching Enabled for GET (Query String) Requests?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This should almost ALWAYS be set to <code>No</code>. UNLESS, you\'re using unfriendly Permalinks. In other words, if all of your URLs contain a query string (e.g. <code>/?key=value</code>); you\'re using unfriendly Permalinks. Ideally, you would refrain from doing this; and instead, update your Permalink options immediately; which also optimizes your site for search engines. That being said, if you really want to use unfriendly Permalinks, and ONLY if you\'re using unfriendly Permalinks, you should set this to <code>Yes</code>; and don\'t worry too much, the sky won\'t fall on your head :-)', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][get_requests]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['get_requests'], '0', FALSE).'>'.__('No, do NOT cache (or serve a cache file) when a query string is present.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['get_requests'], '1', FALSE).'>'.__('Yes, I would like to cache URLs that contain a query string.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> POST requests (i.e. forms with <code>method=&quot;post&quot;</code>) are always excluded from the cache, which is the way it should be. Any <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html" target="_blank">POST/PUT/DELETE</a> request should NEVER (ever) be cached. CLI (and self-serve) requests are also excluded from the cache (always). A CLI request is one that comes from the command line; commonly used by CRON jobs and other automated routines. A self-serve request is an HTTP connection established from your site -› to your site. For instance, a WP Cron job, or any other HTTP request that is spawned not by a user, but by the server itself.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Advanced Tip:</strong> If you are NOT caching GET requests (recommended), but you DO want to allow some special URLs that include query string parameters to be cached; you can add this special parameter to any URL <code>?zcAC=1</code>. This tells %1$s that it\'s OK to cache that particular URL, even though it contains query string arguments. If you ARE caching GET requests and you want to force %1$s to NOT cache a specific request, you can add this special parameter to any URL <code>?zcAC=0</code>.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('404 Requests', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Caching Enabled for 404 Requests?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('When this is set to <code>No</code>, %1$s will ignore all 404 requests and no cache file will be served. While this is fine for most site owners, caching the 404 page on a high-traffic site may further reduce server load. When this is set to <code>Yes</code>, %1$s will cache the 404 page (see <a href="https://codex.wordpress.org/Creating_an_Error_404_Page" target="_blank">Creating an Error 404 Page</a>) and then serve that single cache file to all future 404 requests.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cache_404_requests]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cache_404_requests'], '0', FALSE).'>'.__('No, do NOT cache (or serve a cache file) for 404 requests.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cache_404_requests'], '1', FALSE).'>'.__('Yes, I would like to cache the 404 page and serve the cached file for 404 requests.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>How does %1$s cache 404 requests?</strong> %1$s will create a special cache file (<code>----404----.html</code>, see Advanced Tip below) for the first 404 request and then <a href="http://www.php.net/manual/en/function.symlink.php" target="_blank">symlink</a> future 404 requests to this special cache file. That way you don\'t end up with lots of 404 cache files that all contain the same thing (the contents of the 404 page). Instead, you\'ll have one 404 cache file and then several symlinks (i.e., references) to that 404 cache file.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Advanced Tip:</strong> The default 404 cache filename (<code>----404----.html</code>) is designed to minimize the chance of a collision with a cache file for a real page with the same name. However, if you want to override this default and define your own 404 cache filename, you can do so by adding <code>define(\'ZENCACHE_404_CACHE_FILENAME\', \'your-404-cache-filename\');</code> to your <code>wp-config.php</code> file (note that the <code>.html</code> extension should be excluded when defining a new filename).', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('RSS, RDF, and Atom Feeds', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Caching Enabled for RSS, RDF, Atom Feeds?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This should almost ALWAYS be set to <code>No</code>. UNLESS, you\'re sure that you want to cache your feeds. If you use a web feed management provider like Google® Feedburner and you set this option to <code>Yes</code>, you may experience delays in the detection of new posts. <strong>NOTE:</strong> If you do enable this, it is highly recommended that you also enable automatic Feed Clearing too. Please see the section above: "Clearing the Cache". Find the sub-section titled: "Auto-Clear RSS/RDF/ATOM Feeds".', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][feeds_enable]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['feeds_enable'], '0', FALSE).'>'.__('No, do NOT cache (or serve a cache file) when displaying a feed.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['feeds_enable'], '1', FALSE).'>'.__('Yes, I would like to cache feed URLs.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> This option affects all feeds served by WordPress, including the site feed, the site comment feed, post-specific comment feeds, author feeds, search feeds, and category and tag feeds. See also: <a href="http://codex.wordpress.org/WordPress_Feeds" target="_blank">WordPress Feeds</a>.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('URI Exclusion Patterns', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <h3>'.__('Don\'t Cache These Special URI Exclusion Patterns?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Sometimes there are certain cases where a particular file, or a particular group of files, should never be cached. This is where you will enter those if you need to (one per line). Searches are performed against the <a href="https://gist.github.com/jaswsinc/338b6eb03a36c048c26f" target="_blank" style="text-decoration:none;"><code>REQUEST_URI</code></a>; i.e. <code>/path/?query</code> (caSe insensitive). So, don\'t put in full URLs here, just word fragments found in the file path (or query string) is all you need, excluding the http:// and domain name. A wildcard <code>*</code> character can also be used when necessary; e.g. <code>/category/abc-followed-by-*</code>; (where <code>*</code> = anything, 0 or more characters in length).', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][exclude_uris]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_uris']).'</textarea></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.example.com/post/example-post-123</code>. To exclude this URL, you would put this line into the field above: <code>/post/example-post-123</code>. Or, you could also just put in a small fragment, like: <code>example</code> or <code>example-*-123</code> and that would exclude any URI containing that word fragment.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('HTTP Referrer Exclusion Patterns', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <h3>'.__('Don\'t Cache These Special HTTP Referrer Exclusion Patterns?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Sometimes there are special cases where a particular referring URL (or referring domain) that sends you traffic; or even a particular group of referring URLs or domains that send you traffic; should result in a page being loaded on your site that is NOT from the cache (and that resulting page should never be cached). This is where you will enter those if you need to (one per line). Searches are performed against the <a href="http://www.php.net//manual/en/reserved.variables.server.php" target="_blank" style="text-decoration:none;"><code>HTTP_REFERER</code></a> (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g. <code>*.domain.com</code>; (where <code>*</code> = anything, 0 or more characters in length).', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][exclude_refs]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_refs']).'</textarea></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> let\'s use this example URL: <code>http://www.referring-domain.com/search/?q=search+terms</code>. To exclude this referring URL, you could put this line into the field above: <code>www.referring-domain.com</code>. Or, you could also just put in a small fragment, like: <code>/search/</code> or <code>q=*</code>; and that would exclude any referrer containing that word fragment.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('User-Agent Exclusion Patterns', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <h3>'.__('Don\'t Cache These Special User-Agent Exclusion Patterns?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Sometimes there are special cases when a particular user-agent (e.g. a specific browser or a specific type of device); should be shown a page on your site that is NOT from the cache (and that resulting page should never be cached). This is where you will enter those if you need to (one per line). Searches are performed against the <a href="http://www.php.net//manual/en/reserved.variables.server.php" target="_blank" style="text-decoration:none;"><code>HTTP_USER_AGENT</code></a> (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g. <code>Android *; Chrome/* Mobile</code>; (where <code>*</code> = anything, 0 or more characters in length).', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][exclude_agents]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['exclude_agents']).'</textarea></p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Tip:</strong> if you wanted to exclude iPhones put this line into the field above: <code>iPhone;*AppleWebKit</code>. Or, you could also just put in a small fragment, like: <code>iphone</code>; and that would exclude any user-agent containing that word fragment. Note, this is just an example. With a default installation of %1$s, there is no compelling reason to exclude iOS devices (or any mobile device for that matter).', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Auto-Cache Engine', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Enable the Auto-Cache Engine?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('After using %1$s for awhile (or any other page caching plugin, for that matter); it becomes obvious that at some point (based on your configured Expiration Time) %1$s has to refresh itself. It does this by ditching its cached version of a page, reloading the database-driven content, and then recreating the cache with the latest data. This is a never ending regeneration cycle that is based entirely on your configured Expiration Time.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.__('Understanding this, you can see that 99% of your visitors are going to receive a lightning fast response from your server. However, there will always be around 1% of your visitors that land on a page for the very first time (before it\'s been cached), or land on a page that needs to have its cache regenerated, because the existing cache has become outdated. We refer to this as a <em>First-Come Slow-Load Issue</em>. Not a huge problem, but if you\'re optimizing your site for every ounce of speed possible, the Auto-Cache Engine can help with this. The Auto-Cache Engine has been designed to combat this issue by taking on the responsibility of being that first visitor to a page that has not yet been cached, or has an expired cache. The Auto-Cache Engine is powered, in part, by <a href="http://codex.wordpress.org/Category:WP-Cron_Functions" target="_blank">WP-Cron</a> (already built into WordPress). The Auto-Cache Engine runs at 15-minute intervals via WP-Cron. It also uses the <a href="http://core.trac.wordpress.org/browser/trunk/wp-includes/http.php" target="_blank">WP_Http</a> class, which is also built into WordPress already.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.__('The Auto-Cache Engine obtains its list of URLs to auto-cache, from two different sources. It can read an <a href="http://wordpress.org/extend/plugins/google-sitemap-generator/" target="_blank">XML Sitemap</a> and/or a list of specific URLs that you supply. If you supply both sources, it will use both sources collectively. The Auto-Cache Engine takes ALL of your other configuration options into consideration too, including your Expiration Time, as well as any cache exclusion rules.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][auto_cache_enable]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['auto_cache_enable'], '0', FALSE).'>'.__('No, leave the Auto-Cache Engine disabled please.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['auto_cache_enable'], '1', FALSE).'>'.__('Yes, I want the Auto-Cache Engine to keep pages cached automatically.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="plugin-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('XML Sitemap URL (or an XML Sitemap Index)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">'.esc_html(home_url('/')).'</td><td><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][auto_cache_sitemap_url]" value="'.esc_attr($this->plugin->options['auto_cache_sitemap_url']).'" /></td></tr></table>'."\n";
			if(is_multisite()) echo '<p class="info" style="display:block; margin-top:-15px;">'.sprintf(__('In a Multisite Network, each child blog will be auto-cached too. %1$s will dynamically change the leading <code>%2$s</code> as necessary; for each child blog in the network. %1$s supports both sub-directory &amp; sub-domain networks; including domain mapping plugins.', $this->plugin->text_domain), esc_html($this->plugin->name), esc_html(home_url('/'))).'</p>'."\n";
			echo '         <h3>'.__('And/Or; a List of URLs to Auto-Cache (One Per Line)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][auto_cache_other_urls]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['auto_cache_other_urls']).'</textarea></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Auto-Cache Delay Timer (in Milliseconds)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p>'.__('As the Auto-Cache Engine runs through each URL, you can tell it to wait X number of milliseconds between each connection that it makes. It is strongly suggested that you DO have some small delay here. Otherwise, you run the risk of hammering your own web server with multiple repeated connections whenever the Auto-Cache Engine is running. This is especially true on very large sites; where there is the potential for hundreds of repeated connections as the Auto-Cache Engine goes through a long list of URLs. Adding a delay between each connection will prevent the Auto-Cache Engine from placing a heavy load on the processor that powers your web server. A value of <code>500</code> milliseconds is suggested here (half a second). If you experience problems, you can bump this up a little at a time, in increments of <code>500</code> milliseconds; until you find a happy place for your server. <em>Please note that <code>1000</code> milliseconds = <code>1</code> full second.</em>', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][auto_cache_delay]" value="'.esc_attr($this->plugin->options['auto_cache_delay']).'" /></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Auto-Cache User-Agent String', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <table style="width:100%;"><tr><td><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][auto_cache_user_agent]" value="'.esc_attr($this->plugin->options['auto_cache_user_agent']).'" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;">; '.esc_html(__NAMESPACE__.' '.$this->plugin->version).'</td></tr></table>'."\n";
			echo '         <p class="info" style="display:block;">'.__('This is how the Auto-Cache Engine identifies itself when connecting to URLs. See <a href="http://en.wikipedia.org/wiki/User_agent" target="_blank">User Agent</a> in the Wikipedia.', $this->plugin->text_domain).'</p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('HTML Compression', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Enable WebSharks™ HTML Compression?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p class="notice" style="display:block;">'.__('This is an experimental feature, however it offers a potentially HUGE speed boost. You can <a href="https://github.com/websharks/html-compressor" target="_blank">learn more here</a>. Please use with caution.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_enable]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['htmlc_enable'], '0', FALSE).'>'.__('No, do NOT compress HTML/CSS/JS code at runtime.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['htmlc_enable'], '1', FALSE).'>'.__('Yes, I want to compress HTML/CSS/JS for blazing fast speeds.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info" style="display:block;">'.__('<strong>Note:</strong> This is experimental. Please <a href="https://github.com/websharks/quick-cache/issues" target="_blank">report issues here</a>.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="plugin-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('HTML Compression Options', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p>'.__('You can <a href="https://github.com/websharks/html-compressor" target="_blank">learn more about all of these options here</a>.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_combine_head_body_css]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_head_body_css'], '1', FALSE).'>'.__('Yes, combine CSS from &lt;head&gt; and &lt;body&gt; into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_head_body_css'], '0', FALSE).'>'.__('No, do not combine CSS from &lt;head&gt; and &lt;body&gt; into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_css_code]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_css_code'], '1', FALSE).'>'.__('Yes, compress the code in any unified CSS files.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_css_code'], '0', FALSE).'>'.__('No, do not compress the code in any unified CSS files.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_combine_head_js]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_head_js'], '1', FALSE).'>'.__('Yes, combine JS from &lt;head&gt; into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_head_js'], '0', FALSE).'>'.__('No, do not combine JS from &lt;head&gt; into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_combine_footer_js]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_footer_js'], '1', FALSE).'>'.__('Yes, combine JS footer scripts into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_footer_js'], '0', FALSE).'>'.__('No, do not combine JS footer scripts into fewer files.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_combine_remote_css_js]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_combine_remote_css_js'], '1', FALSE).'>'.__('Yes, combine CSS/JS from remote resources too.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_combine_remote_css_js'], '0', FALSE).'>'.__('No, do not combine CSS/JS from remote resources.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_js_code]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_js_code'], '1', FALSE).'>'.__('Yes, compress the code in any unified JS files.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_js_code'], '0', FALSE).'>'.__('No, do not compress the code in any unified JS files.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_inline_js_code]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_inline_js_code'], '1', FALSE).'>'.__('Yes, compress inline JavaScript snippets.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_inline_js_code'], '0', FALSE).'>'.__('No, do not compress inline JavaScript snippets.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_compress_html_code]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['htmlc_compress_html_code'], '1', FALSE).'>'.__('Yes, compress (remove extra whitespace) in the final HTML code too.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['htmlc_compress_html_code'], '0', FALSE).'>'.__('No, do not compress the final HTML code.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('CSS Exclusion Patterns?', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Sometimes there are special cases when a particular CSS file should NOT be consolidated or compressed in any way. This is where you will enter those if you need to (one per line). Searches are performed against the <code>&lt;link href=&quot;&quot;&gt;</code> value, and also against the contents of any inline <code>&lt;style&gt;</code> tags (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g. <code>xy*-framework</code>; (where <code>*</code> = anything, 0 or more characters in length).', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_css_exclusions]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['htmlc_css_exclusions']).'</textarea></p>'."\n";
			echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <h3>'.__('JavaScript Exclusion Patterns?', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Sometimes there are special cases when a particular JS file should NOT be consolidated or compressed in any way. This is where you will enter those if you need to (one per line). Searches are performed against the <code>&lt;script src=&quot;&quot;&gt;</code> value, and also against the contents of any inline <code>&lt;script&gt;</code> tags (caSe insensitive). A wildcard <code>*</code> character can also be used when necessary; e.g. <code>xy*-framework</code>; (where <code>*</code> = anything, 0 or more characters in length).', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_js_exclusions]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['htmlc_js_exclusions']).'</textarea></p>'."\n";
			echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('HTML Compression Cache Expiration', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][htmlc_cache_expiration_time]" value="'.esc_attr($this->plugin->options['htmlc_cache_expiration_time']).'" /></p>'."\n";
			echo '         <p class="info" style="display:block;">'.__('<strong>Tip:</strong> the value that you specify here MUST be compatible with PHP\'s <a href="http://php.net/manual/en/function.strtotime.php" target="_blank" style="text-decoration:none;"><code>strtotime()</code></a> function. Examples: <code>2 hours</code>, <code>7 days</code>, <code>6 months</code>, <code>1 year</code>.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p>'.sprintf(__('<strong>Note:</strong> This does NOT impact the overall cache expiration time that you configure with %1$s. It only impacts the sub-routines provided by the HTML Compressor. In fact, this expiration time is mostly irrelevant. The HTML Compressor uses an internal checksum, and it also checks <code>filemtime()</code> before using an existing cache file. The HTML Compressor class also handles the automatic cleanup of your cache directories to keep it from growing too large over time. Therefore, unless you have VERY little disk space there is no reason to set this to a lower value (even if your site changes dynamically quite often). If anything, you might like to increase this value which could help to further reduce server load. You can <a href="https://github.com/websharks/HTML-Compressor" target="_blank">learn more here</a>. We recommend setting this value to at least double that of your overall %1$s expiration time.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('GZIP Compression', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/gzip.png')).'" class="screenshot" />'."\n";
			echo '      <h3>'.__('<a href="https://developers.google.com/speed/articles/gzip" target="_blank">GZIP Compression</a> (Optional; Highly Recommended)', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.__('You don\'t have to use an <code>.htaccess</code> file to enjoy the performance enhancements provided by this plugin; caching is handled automatically by WordPress/PHP alone. That being said, if you want to take advantage of the additional speed enhancements associated w/ GZIP compression (and we do recommend this), then you WILL need an <code>.htaccess</code> file to accomplish that part.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.sprintf(__('%1$s fully supports GZIP compression on its output. However, it does not handle GZIP compression directly. We purposely left GZIP compression out of this plugin, because GZIP compression is something that should really be enabled at the Apache level or inside your <code>php.ini</code> file. GZIP compression can be used for things like JavaScript and CSS files as well, so why bother turning it on for only WordPress-generated pages when you can enable GZIP at the server level and cover all the bases!', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.__('If you want to enable GZIP, create an <code>.htaccess</code> file in your WordPress® installation directory, and put the following few lines in it. Alternatively, if you already have an <code>.htaccess</code> file, just add these lines to it, and that is all there is to it. GZIP is now enabled in the recommended way! See also: <a href="https://developers.google.com/speed/articles/gzip" target="_blank"><i class="fa fa-youtube-play"></i> video about GZIP Compression</a>.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <pre class="code"><code>'.esc_html(file_get_contents(dirname(__FILE__).'/gzip-htaccess.tpl.txt')).'</code></pre>'."\n";
			echo '      <hr />'."\n";
			echo '      <p class="info" style="display:block;"><strong>Or</strong>, if your server is missing <code>mod_deflate</code>/<code>mod_filter</code>; open your <strong>php.ini</strong> file and add this line: <a href="http://php.net/manual/en/zlib.configuration.php" target="_blank" style="text-decoration:none;"><code>zlib.output_compression = on</code></a></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Static CDN Filters', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-question-circle fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Enable Static CDN Filters (e.g. MaxCDN/CloudFront)?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('This feature allows you to serve some and/or ALL static files on your site from a CDN of your choosing. This is made possible through content/URL filters exposed by WordPress and implemented by %1$s. All it requires is that you setup a CDN host name sourced by your WordPress installation domain. You enter that CDN host name below and %1$s will do the rest! Super easy, and it doesn\'t require any DNS changes either. :-) Please <a href="http://zencache.com/r/static-cdn-filters-general-instructions/" target="_blank">click here</a> for a general set of instructions.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.__('<strong>What\'s a CDN?</strong> It\'s a Content Delivery Network (i.e. a network of optimized servers) designed to cache static resources served from your site (e.g. JS/CSS/images and other static files) onto it\'s own servers, which are located strategically in various geographic areas around the world. Integrating a CDN for static files can dramatically improve the speed and performance of your site, lower the burden on your own server, and reduce latency associated with visitors attempting to access your site from geographic areas of the world that might be very far away from the primary location of your own web servers.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_enable]">'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['cdn_enable'], '0', FALSE).'>'.__('No, I do NOT want CDN filters applied at runtime.', $this->plugin->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['cdn_enable'], '1', FALSE).'>'.__('Yes, I want CDN filters applied w/ my configuration below.', $this->plugin->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="plugin-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('CDN Host Name (Absolutely Required)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p class="info" style="display:block;">'. // This note includes two graphics. One for MaxCDN; another for CloudFront.
			     '            <a href="http://aws.amazon.com/cloudfront/" target="_blank"><img src="'.esc_attr($this->plugin->url('/client-s/images/cloudfront-logo.png')).'" style="width:75px; float:right; margin: 8px 10px 0 25px;" /></a>'.
			     '            <a href="https://www.maxcdn.com/websharks/" target="_blank"><img src="'.esc_attr($this->plugin->url('/client-s/images/maxcdn-logo.png')).'" style="width:125px; float:right; margin: 20px 0 0 25px;" /></a>'.
			     '            '.__('This one field is really all that\'s necessary to get Static CDN Filters working! However, it does requires a little bit of work on your part. You need to setup and configure a CDN before you can fill in this field. One you configure a CDN, you\'ll receive a host name (provided by your CDN), which you\'ll enter here; e.g. <code>js9dgjsl4llqpp.cloudfront.net</code>. We recommend <a href="https://www.maxcdn.com/websharks/" target="_blank">MaxCDN</a> and/or <a href="http://aws.amazon.com/cloudfront/" target="_blank">Amazon CloudFront</a>, but this should work with many of the most popular CDNs. Please read <a href="http://zencache.com/r/static-cdn-filters-general-instructions/" target="_blank">this article</a> for a general set of instructions. We also have a <a href="http://zencache.com/r/static-cdn-filters-cloudfront/" target="_blank">CloudFront tutorial video</a> that walks you through the process.', $this->plugin->text_domain).'</option>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_host]" value="'.esc_attr($this->plugin->options['cdn_host']).'" /></p>'."\n";
			echo '         <h3>'.__('CDN Host Supports HTTPS Connections?', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><select name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_over_ssl]" autocomplete="off">'."\n";
			echo '               <option value="0"'.selected($this->plugin->options['cdn_over_ssl'], '0', FALSE).'>'.__('No, I don\'t serve content over https://; or I haven\'t configured my CDN w/ an SSL certificate.', $this->plugin->text_domain).'</option>'."\n";
			echo '               <option value="1"'.selected($this->plugin->options['cdn_over_ssl'], '1', FALSE).'>'.__('Yes, I\'ve configured my CDN w/ an SSL certificate; I need https:// enabled.', $this->plugin->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <p class="info" style="display:block;">'.__('Everything else below is 100% completely optional; i.e. not required to enjoy the benefits of Static CDN Filters.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Whitelisted File Extensions (Optional; Comma-Delimited)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_whitelisted_extensions]" value="'.esc_attr($this->plugin->options['cdn_whitelisted_extensions']).'" /></p>'."\n";
			echo '         <p>'.__('If you leave this empty a default set of extensions are taken from WordPress itself. The default set of whitelisted file extensions includes everything supported by the WordPress media library. This includes the following: <code style="white-space:normal; word-wrap:break-word;">'.esc_html(str_replace('|', ',', implode('|', array_keys(wp_get_mime_types())))).'</code>.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <h3>'.__('Blacklisted File Extensions (Optional; Comma-Delimited)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_blacklisted_extensions]" value="'.esc_attr($this->plugin->options['cdn_blacklisted_extensions']).'" /></p>'."\n";
			echo '         <p>'.__('With or without a whitelist, you can force exclusions by explicitly blacklisting certain file extensions of your choosing. Please note, the <code>php</code> extension will never be considered a static resource; i.e. it is automatically blacklisted at all times.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Whitelisted URI Inclusion Patterns (Optional; One Per Line)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_whitelisted_uri_patterns]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cdn_whitelisted_uri_patterns']).'</textarea></p>'."\n";
			echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one inclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p>'.__('If provided, only local URIs matching one of the patterns you list here will be served from your CDN Host Name. URI patterns are caSe-insensitive. A wildcard <code>*</code> will match zero or more characters in any of your patterns. A caret <code>^</code> symbol will match zero or more characters that are NOT the <code>/</code> character. For instance, <code>*/wp-content/*</code> here would indicate that you only want to filter URLs that lead to files located inside the <code>wp-content</code> directory. Adding an additional line with <code>*/wp-includes/*</code> would filter URLs in the <code>wp-includes</code> directory also. <strong>If you leave this empty</strong>, ALL files matching a static file extension will be served from your CDN; i.e. the default behavior.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p>'.__('Please note that URI patterns are tested against a file\'s path (i.e. a file\'s URI, and NOT its full URL). A URI always starts with a leading <code>/</code>. To clarify, a URI is the portion of the URL which comes after the host name. For instance, given the following URL: <code>http://example.com/path/to/style.css?ver=3</code>, the URI you are matching against would be: <code>/path/to/style.css?ver=3</code>. To whitelist this URI, you could use a line that contains something like this: <code>/path/to/*.css*</code>', $this->plugin->text_domain).'</p>'."\n";
			echo '         <h3>'.__('Blacklisted URI Exclusion Patterns (Optional; One Per Line)', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><textarea name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_blacklisted_uri_patterns]" rows="5" spellcheck="false" class="monospace">'.format_to_edit($this->plugin->options['cdn_blacklisted_uri_patterns']).'</textarea></p>'."\n";
			echo '         <p>'.__('With or without a whitelist, you can force exclusions by explicitly blacklisting certain URI patterns. URI patterns are caSe-insensitive. A wildcard <code>*</code> will match zero or more characters in any of your patterns. A caret <code>^</code> symbol will match zero or more characters that are NOT the <code>/</code> character. For instance, <code>*/wp-content/*/dynamic.pdf*</code> would exclude a file with the name <code>dynamic.pdf</code> located anywhere inside a sub-directory of <code>wp-content</code>.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <p class="info" style="display:block;">'.__('<strong>Note:</strong> please remember that your entries here should be formatted as a line-delimited list; e.g. one exclusion pattern per line.', $this->plugin->text_domain).'</p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Query String Invalidation Variable Name', $this->plugin->text_domain).'</h3>'."\n";
			echo '         <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][cdn_invalidation_var]" value="'.esc_attr($this->plugin->options['cdn_invalidation_var']).'" /></p>'."\n";
			echo '         <p>'.sprintf(__('Each filtered URL (which then leads to your CDN) will include this query string variable as an easy way to invalidate the CDN cache at any time. Invalidating the CDN cache is simply a matter of changing the global invalidation counter (i.e. the value assigned to this query string variable). %1$s manages invalidations automatically; i.e. %1$s will automatically bump an internal counter each time you upgrade a WordPress component (e.g. a plugin, theme, or WP itself). Or, if you ask %1$s to invalidate the CDN cache (e.g. a manual clearing of the CDN cache); the internal counter is bumped then too. In short, %1$s handles cache invalidations for you reliably. This option simply allows you to customize the query string variable name which makes cache invalidations possible. <strong>Please note, the default value is adequate for most sites. You can change this if you like, but it\'s not necessary.</strong>', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '         <p class="info" style="display:block;">'.sprintf(__('<strong>Note:</strong> If you empty this field, it will effectively disable the %1$s invalidation system for Static CDN Filters; i.e. the query string variable will NOT be included if you do not supply a variable name.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Dynamic Version Salt', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->plugin->url('/client-s/images/salt.png')).'" class="screenshot" />'."\n";
			echo '      <h3>'.__('<i class="fa fa-flask"></i> <span style="display:inline-block; padding:5px; border-radius:3px; background:#FFFFFF; color:#354913;"><span style="font-weight:bold; font-size:80%;">GEEK ALERT</span></span> This is for VERY advanced users only...', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('<em>Note: Understanding the %1$s <a href="http://zencache.com/r/kb-branched-cache-structure/" target="_blank">Branched Cache Structure</a> is a prerequisite to understanding how Dynamic Version Salts are added to the mix.</em>', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p>'.__('A Version Salt gives you the ability to dynamically create multiple variations of the cache, and those dynamic variations will be served on subsequent visits; e.g. if a visitor has a specific cookie (of a certain value) they will see pages which were cached with that version (i.e. w/ that Version Salt: the value of the cookie). A Version Salt can really be anything.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.__('A Version Salt can be a single variable like <code>$_COOKIE[\'my_cookie\']</code>, or it can be a combination of multiple variables, like <code>$_COOKIE[\'my_cookie\'].$_COOKIE[\'my_other_cookie\']</code>. (When using multiple variables, please separate them with a dot, as shown in the example.)', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.__('Experts could even use PHP ternary expressions that evaluate into something. For example: <code>((preg_match(\'/iPhone/i\', $_SERVER[\'HTTP_USER_AGENT\'])) ? \'iPhones\' : \'\')</code>. This would force a separate version of the cache to be created for iPhones (e.g., <code>/cache/PROTOCOL/HOST/REQUEST-URI.v/iPhones.html</code>).', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p>'.__('For more documentation, please see <a href="http://zencache.com/r/kb-dynamic-version-salts/" target="_blank">Dynamic Version Salts</a>.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.sprintf(__('Create a Dynamic Version Salt For %1$s? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:90%%; opacity:0.5;">150%% OPTIONAL</span>', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <table style="width:100%;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">/cache/PROTOCOL/HOST/REQUEST_URI.</td><td><input type="text" name="'.esc_attr(__NAMESPACE__).'[save_options][version_salt]" value="'.esc_attr($this->plugin->options['version_salt']).'" class="monospace" placeholder="$_COOKIE[\'my_cookie\']" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;"></td></tr></table>'."\n";
			echo '      <p class="info" style="display:block;">'.__('<a href="http://php.net/manual/en/language.variables.superglobals.php" target="_blank">Super Globals</a> work here; <a href="http://codex.wordpress.org/Editing_wp-config.php#table_prefix" target="_blank"><code>$GLOBALS[\'table_prefix\']</code></a> is a popular one.<br />Or, perhaps a PHP Constant defined in <code>/wp-config.php</code>; such as <code>WPLANG</code> or <code>DB_HOST</code>.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="notice" style="display:block;">'.__('<strong>Important:</strong> your Version Salt is scanned for PHP syntax errors via <a href="http://phpcodechecker.com/" target="_blank"><code>phpCodeChecker.com</code></a>. If errors are found, you\'ll receive a notice in the Dashboard.', $this->plugin->text_domain).'</p>'."\n";
			echo '      <p class="info" style="display:block;">'.__('If you\'ve enabled a separate cache for each user (optional) that\'s perfectly OK. A Version Salt works with user caching too.', $this->plugin->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Theme/Plugin Developers', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-puzzle-piece fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.__('Developing a Theme or Plugin for WordPress?', $this->plugin->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('<strong>Tip:</strong> %1$s can be disabled temporarily. If you\'re a theme/plugin developer, you can set a flag within your PHP code to disable the cache engine at runtime. Perhaps on a specific page, or in a specific scenario. In your PHP script, set: <code>$_SERVER[\'ZENCACHE_ALLOWED\'] = FALSE;</code> or <code>define(\'ZENCACHE_ALLOWED\', FALSE)</code>. %1$s is also compatible with: <code>define(\'DONOTCACHEPAGE\', TRUE)</code>. It does\'t matter where or when you define one of these, because %1$s is the last thing to run before script execution ends.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.sprintf(__('Writing "Advanced Cache" Plugins Specifically for %1$s', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p>'.sprintf(__('Theme/plugin developers can take advantage of the %1$s plugin architecture by creating PHP files inside this special directory: <code>/wp-content/ac-plugins/</code>. There is an <a href="https://github.com/websharks/zencache/blob/000000-dev/zencache/includes/ac-plugin.example.php" target="_blank">example plugin file @ GitHub</a> (please review it carefully and ask questions). If you develop a plugin for %1$s, please share it with the community by publishing it in the plugins respository at WordPress.org.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <p class="info">'.sprintf(__('<strong>Why does %1$s have it\'s own plugin architecture?</strong> WordPress loads the <code>advanced-cache.php</code> drop-in file (for caching purposes) very early-on; before any other plugins or a theme. For this reason, %1$s implements it\'s own watered-down version of functions like <code>add_action()</code>, <code>do_action()</code>, <code>add_filter()</code>, <code>apply_filters()</code>.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Import/Export Options', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix">'."\n";
			echo '      <i class="fa fa-arrow-circle-o-up fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.sprintf(__('Import Options from Another %1$s Installation?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p>'.sprintf(__('Upload your <code>%1$s-options.json</code> file and click "Save All Changes" below. The options provided by your import file will override any that exist currently.', $this->plugin->text_domain), __NAMESPACE__).'</p>'."\n";
			echo '      <p><input type="file" name="'.esc_attr(__NAMESPACE__).'[import_options]" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.sprintf(__('Export Existing Options from this %1$s Installation?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <button type="button" class="plugin-menu-page-export-options" style="float:right; margin: 0 0 0 25px;"'. // Exports existing options from this installation.
			     '         data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), __NAMESPACE__ => array('export_options' => '1'))), self_admin_url('/admin.php'))).'">'.
			     '         '.sprintf(__('%1$s-options.json', $this->plugin->text_domain), __NAMESPACE__).' <i class="fa fa-arrow-circle-o-down"></i></button>'."\n";
			echo '      <p>'.sprintf(__('Download your existing options and import them all into another %1$s installation; saves time on future installs.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-save">'."\n";
			echo '   <button type="submit">'.__('Save All Changes', $this->plugin->text_domain).' <i class="fa fa-save"></i></button>'."\n";
			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '</div>'."\n";
			echo '</form>';
		}

		public function pro_updater()
		{
			echo '<form id="plugin-menu-page" class="plugin-menu-page" method="post" enctype="multipart/form-data"'.
			     ' action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-pro-updater', '_wpnonce' => wp_create_nonce())), self_admin_url('/admin.php'))).'">'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-heading">'."\n";

			echo '   <button type="submit" style="float:right; margin-right:1.5em;">'.__('Update Now', $this->plugin->text_domain).' <i class="fa fa-magic"></i></button>'."\n";

			echo '   <div class="plugin-menu-page-panel-togglers" title="'.esc_attr(__('All Panels', $this->plugin->text_domain)).'">'."\n";
			echo '      <button type="button" class="plugin-menu-page-panels-open"><i class="fa fa-chevron-down"></i></button>'."\n";
			echo '      <button type="button" class="plugin-menu-page-panels-close"><i class="fa fa-chevron-up"></i></button>'."\n";
			echo '   </div>'."\n";

			echo '   <div class="plugin-menu-page-upsells">'."\n";
			if(current_user_can($this->plugin->cap)) echo '<a href="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__)), self_admin_url('/admin.php'))).'"><i class="fa fa-gears"></i> '.__('Options', $this->plugin->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://zencache.com/r/zencache-subscribe/').'" target="_blank"><i class="fa fa-envelope"></i> '.__('Newsletter (Subscribe)', $this->plugin->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://zencache.com/r/zencache-beta-testers-list/').'" target="_blank"><i class="fa fa-envelope"></i> '.__('Beta Testers (Signup)', $this->plugin->text_domain).'</a>'."\n";
			echo '   </div>'."\n";

			echo '   <img src="'.$this->plugin->url('/client-s/images/pro-updater.png').'" alt="'.esc_attr(__('Pro Plugin Updater', $this->plugin->text_domain)).'" />'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<hr />'."\n";

			/* ----------------------------------------------------------------------------------------- */

			if(!empty($_REQUEST[__NAMESPACE__.'__error'])) // Error?
			{
				echo '<div class="plugin-menu-page-error error">'."\n";
				echo '   <i class="fa fa-thumbs-down"></i> '.esc_html(stripslashes((string)$_REQUEST[__NAMESPACE__.'__error']))."\n";
				echo '</div>'."\n";
			}
			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-body">'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading open">'."\n";
			echo '      <i class="fa fa-sign-in"></i> '.__('Update Credentials', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix open">'."\n";
			echo '      <i class="fa fa-user fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.sprintf(__('%1$s™ Authentication', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p>'.sprintf(__('From this page you can update to the latest version of %1$s Pro for WordPress. %1$s Pro is a premium product available for purchase @ <a href="http://%2$s/" target="_blank">%2$s</a>. In order to connect with our update servers, we ask that you supply your account login details for <a href="http://%2$s/" target="_blank">%2$s</a>. If you prefer not to provide your password, you can use your License Key in place of your password. Your License Key is located under "My Account" when you log in @ <a href="http://%2$s/" target="_blank">%2$s</a>. This will authenticate your copy of %1$s Pro; providing you with access to the latest version. You only need to enter these credentials once. %1$s Pro will save them in your WordPress database; making future upgrades even easier. <i class="fa fa-smile-o"></i>', $this->plugin->text_domain), esc_html($this->plugin->name), esc_html($this->plugin->domain)).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.sprintf(__('Customer Username', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p><input type="text" name="'.esc_attr(__NAMESPACE__).'[pro_update][username]" value="'.esc_attr($this->plugin->options['pro_update_username']).'" autocomplete="off" /></p>'."\n";
			echo '      <h3>'.sprintf(__('Customer Password or Product License Key', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p><input type="password" name="'.esc_attr(__NAMESPACE__).'[pro_update][password]" value="'.esc_attr($this->plugin->options['pro_update_password']).'" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-panel">'."\n";

			echo '   <a href="#" class="plugin-menu-page-panel-heading open">'."\n";
			echo '      <i class="fa fa-bullhorn"></i> '.__('Update Notifier', $this->plugin->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="plugin-menu-page-panel-body clearfix open">'."\n";
			echo '      <i class="fa fa-rss fa-4x" style="float:right; margin: 0 0 0 25px;"></i>'."\n";
			echo '      <h3>'.sprintf(__('%1$s™ Update Notifier', $this->plugin->text_domain), esc_html($this->plugin->name)).'</h3>'."\n";
			echo '      <p>'.sprintf(__('When a new version of %1$s Pro becomes available, %1$s Pro can display a notification in your WordPress Dashboard prompting you to return to this page and perform an upgrade. Would you like this functionality enabled or disabled?', $this->plugin->text_domain), esc_html($this->plugin->name)).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <p><select name="'.esc_attr(__NAMESPACE__).'[pro_update][check]" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->plugin->options['pro_update_check'], '1', FALSE).'>'.sprintf(__('Yes, display a notification in my WordPress Dashboard when a new version is available.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</option>'."\n";
			echo '            <option value="0"'.selected($this->plugin->options['pro_update_check'], '0', FALSE).'>'.sprintf(__('No, do not display any %1$s update notifications in my WordPress Dashboard.', $this->plugin->text_domain), esc_html($this->plugin->name)).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '<div class="plugin-menu-page-save">'."\n";
			echo '   <button type="submit">'.__('Update Now', $this->plugin->text_domain).' <i class="fa fa-magic"></i></button>'."\n";
			echo '</div>'."\n";

			/* ----------------------------------------------------------------------------------------- */

			echo '</div>'."\n";
			echo '</form>';
		}
	}
}
