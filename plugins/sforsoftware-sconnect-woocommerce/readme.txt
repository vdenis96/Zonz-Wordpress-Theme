=== SforSoftware Sconnect Woocommerce ===
Contributors: SforSoftware
Tags: woocommerce, sconnect, snelstart, sforsoftware
Requires at least: 4.8
Tested up to: 4.9.7
Stable tag: 1.0.0
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin extends WooCommerce by connecting to Snelstart sConnect.

== Description ==
S-Connect is een koppeling tussen uw webshop en uw SnelStart-administratie. Orders uit uw webshop worden automatisch verwerkt in SnelStart.

S-Connect importeert orders uit de webshop en maakt automatisch voor elke bestelling een order aan in SnelStart. Daarbij worden klanten slim herkend, en kunnen zowel klanten als artikelen in SnelStart worden aangemaakt.

Met deze koppeling tussen webshop en SnelStart houdt u tijd over voor het echte werk: de order verzamelen en versturen.
De voordelen: uw service is sneller, efficiënter en foutloos!

== Installation ==
Place the plugin in the WordPress plugins folder and enable your plugin. After this, configure your settings and you are ready to go!

== Frequently Asked Questions ==

= Where are the settings =

The plugin settings can be found under the settings. There is a submenu called SforSoftware Sconnect WooCommerce, here you can register your plugin settings.

== Changelog ==

= 1.0.26 - 2018.08.28 =
Made inventory query so that the parent of variable products doesn't get send.

= 1.0.24 - 2018.07.19 =
Made the count on products/inventory update php 7.2 compatible

= 1.0.23 - 2018.07.04 =
Categories are now added in the extensions and not in the core plugin.

= 1.0.22 - 2018.07.04 =
Updated Category add in core plugin. Now the extensions can send a array of category names that will be created and applied.

= 1.0.21 - 2018.06.27 =
Added Category Add in core plugin, Added add omzetgroep as categorie option in admin settings.

= 1.0.20 - 2018.06.27 =
Added Rest Authorization bypass

= 1.0.19 - 2018.06.16 =
New method for adding post meta to products

= 1.0.18 - 2018.06.11 =
Added order memo to the orders.

= 1.0.17 - 2018.06.05 =
Added before product update filter hook.

= 1.0.16 - 2018.06.04 =
Added before product save filter hook.

= 1.0.15 - 2018.05.31 =
Added small bug fix in orders. When a order gets cancelled the order is removed form the export table.

= 1.0.14 - 2018.05.18 =
Added multiple filter hooks

= 1.0.13 - 2018.05.17 =
Added sfs_before_product_save filter

= 1.0.12 - 2018.05.17 =
Updated products import. Now categories get added if they don't exist (this is optional!) and the categories are added to the products.

= 1.0.11 - 2018.04.20 =
Added _get_totals_tax in order command. This function divides the total tax with the product quantity.

= 1.0.10 - 2018.04.10 =
Added a warning if Woocommerce if out of date.

= 1.0.9 - 2018.04.03 =
Product inventory fixed variable products not returning child quantity

= 1.0.8 - 2018.03.23 =
Product update added a hook after post meta is set.

= 1.0.7 - 2018.03.20 =
Fixed a bug where the stock status didn't update if the stock quantity was more than 0

= 1.0.6 - 2018.03.19 =
Deleting orders now deletes the order row in the export table, Un trashing a order re adds the order row in the export table

= 1.0.5 - 2018.03.16 =
Reworked orders.php orders slq, so the pagination starts at 0

= 1.0.4 - 2018.02.28 =
Reworked orders.php orders slq, so the pagination starts at 1

= 1.0.3 - 2018.02.19 =
Added 'ssw_after_order' filter, filter is used to filter product data on the orders.
Added _get_product_manage_stock function that return a boolean of the stock status for the product.

= 1.0.2 - 2018.02.19 =
Updated orders functions.

= 1.0.1 - 2018.02.16 =
Updated orders functions.

= 1.0.0 - 2017.09.18 =
Created plugin

== Upgrade Notice ==

= 1.0.0 - 2017.09.18 =
This version requires WooCommerce 3.1.2

== Screenshots ==