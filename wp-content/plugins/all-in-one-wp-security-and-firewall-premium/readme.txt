=== All In One WP Security & Firewall Premium ===
Contributors: UpdraftPlus.Com, DavidAnderson
Tags: country blocking, IP address blocking based on country, 404 blocking, IP address blocking based on 404
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 1.0.3
License: GPLv3 or later

The All In One WP Security & Firewall Premium addon plugin allows website owners to selectively block visitors whose IP addresses originate from certain countries and exceed a configurable amount of maximum 404 HTTP events.

== Description ==
= This addon will block IP addresses based on the country of origin and whether they exceed a maximum amount of HTTP 404 events within a time period =

= Plugin Support =
* If you have a question or problem with the All In One WP Security & Firewall Premium Addon Plugin, please post it on our customer-only support forum.

== Installation ==

To install the All In One WP Security & Firewall Premium Plugin:

1. Ensure that you already have the All In One WP Security and Firewall plugin installed and activated
2. Upload the 'all-in-one-security-and-firewall-premium.zip' file from the Plugins->Add New page in the WordPress administration panel.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to WP Security and select 'Country Blocking' or 'Smart 404' and start activating the features of this addon.

== Usage ==
After installing and activating the All In One WP Security & Firewall Premium addon plugin, go to the WP Security->Country Blocking menu or the WP Security->Smart 404 menu.

General Country Blocking
------------------------
1) Select the "Enable Country Blocking" checkbox

2) Enter a URL in the "Redirect URL" field. This is where you want your blocked users to be redirected to when they try to access your site.
You can create a page on your own site or you can enter any external URL or http://127.0.0.1

3) Select the country or countries you wish to block by checking the appropriate checkboxes

4) Click the "Save Settings" button to save your configuration.

Secondary Country Blocking Settings
-----------------------------------
The secondary settings on this page allow you to further refine how you block visitors based on the specific pages and posts they try to visit.
The countries you selected in the "General Settings" tab will be blocked from the whole site. (You can think of the "General Settings" as the first line of blocking defense)
The settings on this page are for those countries which have not been blocked sitewide. These settings allow you to select which countries will be blocked or allowed access to specific pages or posts specified in the configuration below. (You can think of these settings as the second line of blocking defence)
This feature can be very useful for eCommerce situations such as when merchants wish to confine their online sales to people from certain countries due to shipping or tax constraints.

(These settings will apply to those visitors who were not blocked by the "General Settings" configuration. If your "General Settings" config is disabled or no country is selected, then the "Secondary Settings" will apply to all visitors.)

To use the secondary blocking feature:
1) Select the "Enable Secondary Country Blocking:" checkbox

2) Enter the page or post IDs you wish to protect. Each ID should be on a new line.

3) Enter a URL in the "Redirect URL" field. This is where you want your blocked users to be redirected to when they try to access your site.
You can create a page on your own site or you can enter any external URL or http://127.0.0.1

4) Select the country or countries you wish to block by checking the appropriate checkboxes

5) Click the "Save Settings" button to save your configuration.

Whitelist Settings
-----------------------------------
The Whitelist settings tab enables you to allow certain IP addresses or address ranges to have access to your site if they come from a blocked country.
In other words you can specify exceptions to the normal blocking rules here.
Example 1: 
Let's say you have selected to block Ukraine but you wish to allow one IP address from this country (217.77.250.207). You would enable whitelisting and simply enter this IP address in the white list address box and save the settings.

Example 2: 
Let's say you have selected to block Ukraine but you wish to allow a block of IP addresses from this country (217.77.250.*). You would enable whitelisting and simply enter 217.77.250.* in the white list address box and save the settings.

== Frequently Asked Questions ==

== Changelog ==

= 1.0.3 - 14/Sep/2023 =

* FEATURE: Added WP-CLI command `wp aios` to set and reset some of AIOS features.
* FIX: Corrected broken link for 'View All 404 Events' button on Smart 404 > Blocked IPs tab.
* FIX: Class AIOWPS_Premium_MaxMind_settings not found error when running WP CLI commands
* TWEAK: Reset the AIOS premium configuration entries also when the free plugin's settings page "Reset settings" button is pressed.
* TWEAK: Improve internal code structure making way for future improvements
* TWEAK : Resolve various deprecation notices

= 1.0.2 - 24/March/2023 =

* FIX: Call to undefined method AIOWPSecurity_Utility_IP::is_ip_whitelisted() fixed.
* TWEAK: Allow AIOS management permission to be filtered via `aios_management_permission` filter
* TWEAK: Prevent country blocking addon from hiding page when the user is logged in
* TWEAK: Improve internal code structure making way for future improvements

= 1.0.1 - 16/November/2022 =

* SECURITY: Fixed a failure to check bulk action nonces, leading to a CSRF vulnerability. Exploitation would require an attacker to craft a link specifically for your site, and persuade you to click it whilst logged in; if you did so, this could result in bulk actions being carried out on AIOS list tables (e.g. delete entries from blocked IP address lists), with the attacker being restricted to deleting entries by database ID numbers that he cannot know directly (e.g. 15, 16, 17) and not IP address (e.g. 100.101.102.103).
* FIX: Resolved uncaught error Undefined constant "AIOWPSEC_MANAGEMENT_PERMISSION", When deactivation the AIOS free plugin.
* TWEAK: The plugin should be activated network wide only in multisite.
* TWEAK: Set a global context for $wp_file_descriptions context so that it gets assigned to correctly, preventing a subtle visual change in the theme editor

= 1.0.0 =
* First version released.

== Upgrade Notice ==
* 1.0.3: Added WP-CLI commands to set and reset some of AIOS features. Various internal tweaks. See changelog for full details. A recommended update for all.

