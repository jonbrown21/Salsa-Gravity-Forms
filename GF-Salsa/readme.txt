=== Plugin Name ===
Contributors: jonbrown33
Tags: gravity forms, forms, salsa, salsa labs
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 4.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Salsa for Gravity Forms plugin adds the ability to feed user signup data into Salsa Labs email platform for non-profits easily.

== Description ==

The Salsa for Gravity Forms plugin adds the ability to feed user signup data into Salsa Labs email platform for non-profits easily.

You can map groups to checkboxes or dropdown lists to allow users to pick a list to subscribe to in various ways. You can set admin groups, which allow you to funnel a user signup to a specific list and you can easily add a full list of all your groups for the user to choose from when signing up.

User data is mapped at the field level, map first name, last name, email address, and any other custom field data that you may have, the data will be saved to the gravity forms database automatically and sent to Salsa. To enable Salsa transmission simply set that Salsa option in the form settings.

Features:

*   Easily map user data from your form to Salsa fields.
*   Easily add a full list of groups to your form.
*   Easily map checkboxes or dropdown forms to various group ID's
*   Ability to map data to Salsa custom fields
*   Ability to enable or disable transmission to Salsa
*   Ability to setup admin groups or hidden groups that users will automatically be added to


== Installation ==

Before installing you must have 

1. Install and Activate Gravity Forms.
2. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Gravity Form Integration with SalsaLabs, screen to configure the plugin
4. Once activated click on Forms->Settings->Salsa to add your Organization ID, and a super user, username and password for your Salsa Labs instance.

== Frequently Asked Questions ==

= Where do I find my organization ID in Salsa? =

In the profile menu dropdown you will see your organization ID. Follow this knowlege base article for detailed instructions

https://help.salsalabs.com/entries/23473682-Where-s-my-organization-key-

= What level of access does the Salsa credentials need to be? =

In order to use the Salsa API you must add super user credentials for your Salsa instance. This is a super user, campaign manager account.

= How do I add groups? =

You can add groups by dragging the group field from the standard fields list into your gravity forms, or you can map groups to checkboxes or dropdown field items by using the appropriate gravity forms group item.

= How do I map fields? =

You map fields at the field level, simply edit the field, and select the appropriate Salsa element that your field matches to, for example First Name would map to f_name in the list, if you do not map fields, then no data will flow into salsa. 

= No data is going into salsa, what gives? =

You likely forgot to enable Salsa for the form. There is a Salsa form option dropdown at the form level with a single checkbox that tells the form, this is a salsa form and it should transmit mapped fields and data to salsa.  

== Screenshots ==

1. State Group item in Gravity Forms.
2. Map groups to standard choice fields.
3. Add State fields and map to corresponding Salsa Group IDs.
4. Map state fields or other bulk fields to Salsa Group IDs.
5. Primary authentication connection screen
6. Map standard fields to Salsa fields or custom fields.
7. Setup the Salsa data transmission.
8. Enable Salsa on a form

== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.0 =
Initial Release