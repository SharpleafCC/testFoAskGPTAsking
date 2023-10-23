# Ei Digital WP Base Theme

- Prod URL: <>
- Staging URL: <>
- Dev URL: <>

## Frontend Framework

- TODO add details here about Frow

### Links to more documentation

- https://developer.wordpress.org/
- https://eduardoboucas.github.io/include-media

----

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 5
* [PHP](http://php.net/manual/en/install.php) >= 7.1
* [Node.js](http://nodejs.org/) >= 6.9.x
* [Gulp](https://gulpjs.com/) >= 4.0

----
## Setup
* Clone repo
* To start work on an issue, create a branch off `master`
* Get local environment running
    * Import database
* Run the following commands
  * `yarn install`
  * `gulp watch`

## Build Commands
* gulp watch
  * Watches all scss and js folders wihtin /assets/local for changes and builds files on save to /assets/prod/.
* gulp
  * Will build all css, js and move images to the /assets/prod/ folder for deployment. This can be useful if you have a merge conflict in a css or js file.

## Deployment
Set up a PR for your feature branch for review by team members as needed.

## Staging
Merge feature branch into staging branch and deploy via Github Actions

## Production

Merge feature branch into master branch and deploy via Github Actions

### Deploy Scripts
In the .github/workflows folder, there are 3 .yml files. In this folder do a find/replace on the following keywords:
* THEMENAME -> The name of the theme folder
* PRODENV -> The name of the WPE production environemnt
* STAGEENV -> The name of the WPE staging environment
* DEVENV -> The name of the WPE development environment


## Overview of WordPress Theme Options and Custom Features

### Custom Page Class For Dynamically Loading Unique CSS and JS files per page/post.

An ACF field has been setup that allows you to set a unique page class on all pages and posts in the Wordpress admin. The custom page class var should match both the scss and js filenames for the unique page that you are setting up. You can extend this to Custom Post Type single pages and Custom Taxonomy's via the same ACF fields, but will need to use the theme settings area for custom post type archive pages. Below you will find details for how to setup a new scss or js file.

### SCSS Setup

Within the theme folder assets/local/sass you will find the main app.scss file. This file holds the Global styles and component imports. You should only add new imports to this file IF they will be used across more than one page/global. If you find yourself building something unique to a single page please setup a new file in the /assets/local/pages direcotry. ** Make sure the name does not start with an _ ** All other files that will be imported into the main app.scss should start with an _. When creating a new unique sass page/file make sure to add the @import "../app.scss"; at the top to ensure you inherit all the site wide styles.

Unique pages are setup/deployed by adding a custom page class in the Wordpress admin. This page class MUST match the name of the file you create in the /assets/local/sass/pages directory in order for the file to be dynamically imported. You can find this code in /includes/scripts.php. Once the file has been setup and the page class added in the admin the new page and styles will be enqueued on page load.

### JS Setup

Within the theme folder assets/local/js you will find a sturcture/setup that follows the Sage JavaScript DOM-based routing. Documentation for this can be found here: https://roots.io/docs/sage/9.x/compiling-assets/#javascript-dom-based-routing

The main difference is that we have adjusted the setup to allow for custom scripts per page for a more component based system, just like the SASS setup above. By adding new files to the /assets/local/js/pages directory you can have code that is isolated to that speicifi page while also pulling in the global commin.js base. This is also based off of the custom page class that is added in the admin and MUST match the filename. You will find the JS file enqued within the includes/scripts.php file.

In order for the new file to be compiled via Gulp you need to update the /assets/local/gulp/config.js object array. Follow the format of the main js line: "main: 'assets/local/js/main.js'," but place the new file in a pages folder.

Ex: home: 'assets/local/js/pages/main.js',

### Age Gate Requirements
In order to use elementor popups to facilitate the age gate requirements, a few things must be set up correctly.
Firstly, the popup itself must be made via Templates->Popups->Add New.
In the Elementor editor, open the settings by clicking the bottom left cog icon. Then, select the Advanced tab up top. In here, turn "Prevent Closing on Overlay", "Prevent Closing on ESC key" and "Disable Page Scrolling" to "yes".

|:bangbang: | if you toggle "Avoid Multiple Popups" on, it may prevent the Age Gate from loading. Therefore, it's best to leave that one off. |
|:---: | :---: |

In the "Open By Selector" input box, enter `.age-gate-trigger` (with the preceding period).
Next, in the "CSS Classes" input box, enter `age-gate` (with _no_ period).
When saving/updating the popup you will be presented with display conditions. If you do not see the display conditions, click the up arrow to the right of the update button and select "Display Conditions". Click the "add condition" button and set it to `include` the `entire site`.
For the "Triggers" and "Advanced Rules" tabs, all options should be turned _off_. The triggers will be set via JavaScript which you can see in  [/wp-content/themes/kraken/assets/local/js/routes/common.js](/wp-content/themes/kraken/assets/local/js/routes/common.js)
In order for the popup to be closed, there needs to be an elementor button on the page (or, any element where you can set an ID). In the buttons Content tab, find the "ButtonID" input and enter `age-gate__button--yes` (no hashtag).
This is all that's required of the elementor popup template.

The final item that's needed is a special `<div>` that the JavaScript uses to open the popup.
Open the `wp-content/themes/kraken/header.php` file. Just under the `<body>` tag, enter `<div class="age-gate-trigger" style="display:none"></div>` and save. This div has the `age-gate-trigger` class that was set in the "Open By Selector" input of the elementor popup and the JavasScript sends it a click event to open the popup, if the js-cookie `age-gate-passed` is not set. The JavaScript also creates the `age-gate-passed` js-cookie and sets it to expire in 365 days when the button with the `age-gate__button--yes` ID that was set earlier is clicked.

### Custom CSS and JS name Mapping

For Custom Post Type archive pages there is no page to set the ACF custom field. There is a repeater field set up in the theme settings options page: /wp-admin/admin.php?page=theme-settings where you can add the name of the file you want to load for archive pages. You can also allow it to fall back to the site global file.

# Store Locator
The new Store Locator plugin is no included with the Ei Base Theme. Import the below json into the CPT UI Plugin before you run the store locator updater page funciton.

```json
{
	"locator-flavors": {
		"name": "locator-flavors",
		"label": "Locator Flavors",
		"singular_label": "Locator Flavor",
		"description": "",
		"public": "true",
		"publicly_queryable": "false",
		"show_ui": "true",
		"show_in_nav_menus": "true",
		"delete_with_user": "false",
		"show_in_rest": "true",
		"rest_base": "",
		"rest_controller_class": "",
		"has_archive": "false",
		"has_archive_string": "",
		"exclude_from_search": "true",
		"capability_type": "post",
		"hierarchical": "false",
		"can_export": "true",
		"rewrite": "true",
		"rewrite_slug": "",
		"rewrite_withfront": "true",
		"query_var": "true",
		"query_var_slug": "",
		"menu_position": "",
		"show_in_menu": "true",
		"show_in_menu_string": "",
		"menu_icon": "dashicons-location",
		"supports": [
			"title",
			"thumbnail"
		],
		"taxonomies": [],
		"labels": {
			"menu_name": "",
			"all_items": "",
			"add_new": "",
			"add_new_item": "",
			"edit_item": "",
			"new_item": "",
			"view_item": "",
			"view_items": "",
			"search_items": "",
			"not_found": "",
			"not_found_in_trash": "",
			"parent_item_colon": "",
			"featured_image": "",
			"set_featured_image": "",
			"remove_featured_image": "",
			"use_featured_image": "",
			"archives": "",
			"insert_into_item": "",
			"uploaded_to_this_item": "",
			"filter_items_list": "",
			"items_list_navigation": "",
			"items_list": "",
			"attributes": "",
			"name_admin_bar": "",
			"item_published": "",
			"item_published_privately": "",
			"item_reverted_to_draft": "",
			"item_scheduled": "",
			"item_updated": ""
		},
		"custom_supports": "",
		"enter_title_here": ""
	}
}
```


```json
{
    "locator_flavor_groups": {
        "label": "Locator Flavor Groups",
        "description": "",
        "hierarchical": true,
        "post_types": [
            "locator-flavors"
        ],
        "public": true,
        "publicly_queryable": true,
        "update_count_callback": "",
        "sort": false,
        "labels": [],
        "show_ui": true,
        "show_in_menu": true,
        "show_in_nav_menus": true,
        "show_tagcloud": false,
        "show_in_quick_edit": true,
        "show_admin_column": true,
        "rewrite": true,
        "show_in_rest": false,
        "rest_base": "",
        "rest_controller_class": "WP_REST_Terms_Controller",
        "acfe_single_template": "",
        "acfe_single_ppp": 10,
        "acfe_single_orderby": "date",
        "acfe_single_order": "DESC",
        "acfe_admin_ppp": 10,
        "acfe_admin_orderby": "name",
        "acfe_admin_order": "ASC",
        "capabilities": [],
        "meta_box_cb": null
    }
}
```

### Disabling plugins on Local Setups
Sometimes, 2FA, CAPTCHAs and other plugins don't work on local environments due to domain restrictions and other factors.

To disable certain plugins from loading on your local environment add the following lines to your `wp-config.php` file

```
define('DEV_DISABLED_PLUGINS', serialize([
	'wp-2fa/wp-2fa.php',
	'login-recaptcha/login-nocaptcha.php',
	'all-in-one-wp-security-and-firewall/wp-security.php'
]));
```

replace the array of strings with the paths to the .php files of the plugins you don't want to load. 
They will appear of deactivated in the plugins list in the WP Admin, however, they won't be flagged as such in the database. 

This works becuase of the `mu-plugins/dev-plugin-disabler.php` and `mu-plugins/vendor/DisablePlugins.php` files