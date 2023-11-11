=== Custom Shipping Methods for WooCommerce - Create Weight based Shipping, Conditional Shipping, Table Rate Shipping and much more ===
Contributors: dhruvin, ImagiSol
Tags: woocommerce, shipping, custom shipping, weight based shipping, conditional shipping, woocommerce shipping, shipping methods, advanced shipping, quantity shipping, shipping fees, fees, volume shipping, table rate shipping, own shipping, local shipping
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: trunk
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Configure advanced shipping options for your WooCommerce store with custom shipping methods. Be it weight based shipping or volume based shipping or quantity based shipping or any other conditional shipping. You can also add custom fees based on certain conditions. Show or hide shipping methods based on Product Categories or Product Tags.

== Description ==

**[Custom Shipping Methods for WooCommerce](https://imaginate-solutions.com/downloads/custom-shipping-methods-for-woocommerce/)** plugin lets you add (multiple) custom shipping methods to WooCommerce.

= Shortcodes for cost calculation =

In each custom shipping method's **cost calculation formula** you can use these shortcodes:

* **`[qty]`** - number of items (i.e. **quantity**) in cart,
* **`[cost]`** - total **cost** of items in cart,
* **`[weight]`** - total cart items **weight**,
* **`[volume]`** - total cart items **volume**,
* **`[fee]`** - percentage based **fees**,
* **`[round]`** - **rounding**.

> I had been looking for a plugin for hours and days that would give me the options I was looking for and this has succeeded. In addition to all that, i needed an extra option and these guys added it overnight, impeccable attention, friendly and fast. Sincerely anything i need about plugins I will talk to them to see if they have it or if they can develop it.
>
> **Everything I need and fantastic support!**
> [Yeray](https://wordpress.org/support/topic/everything-i-need-and-fantastic-support/)

= Rules for each shipping method =

In addition, for each custom shipping method, you can optionally set these **method availability** options:

* min and max cart **cost**,
* min and max cart **weight**,
* min and max cart **volume**,
* min and max cart **quantity**,
* required and excluded cart **products**,
* required and excluded cart **product categories**,
* required and excluded cart **product tags**.

= Costs based on Product Shipping Class =

Optionally costs can be added based on the **product shipping class**:

* costs per **product shipping class** and for **no shipping class**,
* **calculation type**: charge shipping per class or per order,
* **limits calculation**: check limits per class, per order or all.

Advanced options include **custom return URL** - it can be used instead of the standard "Order received" page.

In addition to the above, you can also create customized templates for icons that would be displayed besides the shipping method title.
Also, add custom text with HTML for Free Shipping i.e Zero cost.

= Premium Version =

[Custom Shipping Methods for WooCommerce Pro](https://imaginate-solutions.com/downloads/custom-shipping-methods-for-woocommerce/) plugin version also has:

* **`[costs_table]`** shortcode for **table rate shipping** - Costs are calculated using Google Distance Matrix API,
* **`[distance]`** shortcode for **distance based cost calculation**,
* options to set **free shipping minimum order amount**,
* options to set **free shipping products**,
* options to set **min and max cost limits**,
* options to set **min and max distance availability**,
* options to set custom shipping methods' frontend **icons** and **descriptions**.

= Our other plugins =

* [Custom Payment Gateways for WooCommerce](https://imaginate-solutions.com/downloads/custom-payment-gateways-for-woocommerce/) - Create custom payment gateways for your WooCommerce store to add more payment options for the user to choose from.

* [Payment Gateways by User Role](https://imaginate-solutions.com/downloads/payment-gateways-by-user-roles-for-woocommerce/) - Allow payment gateways to be available or not available for only particular user roles.

* [WooCommerce Addon Uploads](https://imaginate-solutions.com/downloads/woocommerce-addon-uploads/) - Create a print on demand store by allowing users to upload their files when adding products to cart on your WooCommerce store.

* [Variations Radio Buttons for WooCommerce](https://imaginate-solutions.com/downloads/variations-radio-buttons-for-woocommerce/) - Convert your variations dropdown into radio buttons there by allowing customers a much better user experience and speeding up the checkout process.

* [WooCommerce Variations Reports](https://imaginate-solutions.com/downloads/woocommerce-variations-reports/) - Get a report of how your variations sales are happening on your WooCommerce Store.

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://imaginate-solutions.com/contact-us/).

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Custom Shipping Methods".

== Screenshots ==

1. Custom Shipping Global Settings.
2. Icons and Description settings.
3. Individual custom shipping options.
4. Other rules part of Pro.
5. Cost, Weight, Volume and Quantity rules.
6. Products, Categories and Tags rules.
7. Shipping Class costs.

== Changelog ==

= 1.9.0 - 11/11/2023 =
* Compatibility with HPOS
* Compatibility with PHP 8.2

= 1.8.0 - 16/09/2021 =
* Fix - Required Categories and Required Tags rules were not applied for Custom Shipping methods.
* WC tested up to: 5.6

= 1.7.0 - 09/07/2021 =
* Dev - Filter added to modify the totals. This can be used to consider totals with taxes.
* Dev - Refactorted the code as per WPCS standards.
* WC tested up to: 5.4

= 1.6.3 - 22/03/2020 =
* WC tested up to: 4.0.

= 1.6.2 - 18/02/2020 =
* Fix - Admin Settings - Text domain fixed.
* Dev - Availability - "Require type" option added.

= 1.6.1 - 22/01/2020 =
* Dev - Shortcodes - `[round]` shortcode added.
* Dev - Code refactoring.
* WC tested up to: 3.9.

= 1.6.0 - 30/12/2019 =
* Dev - Availability - "Required/Excluded products/categories/tags" options added.
* Dev - "Free shipping products" option added.
* Dev - Settings - Icons & Descriptions - Placeholders list added to the description.
* Dev - Code refactoring.

= 1.5.3 - 11/12/2019 =
* Dev - Shortcodes - `[distance]` - Caching results now.
* Dev - Code refactoring.

= 1.5.2 - 06/12/2019 =
* Dev - Availability - "Min distance" and "Max distance" options added.
* Dev - Advanced - "Custom return URL" option added.
* Dev - Shortcodes - `[costs_table]` - Evaluating math expressions in costs now.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.

= 1.5.1 - 13/11/2019 =
* Dev - Shortcodes - `[distance]` - Returning `default_distance` at once when source or destination address is empty.
* WC tested up to: 3.8.
* Tested up to: 5.3.

= 1.5.0 - 23/10/2019 =
* Feature - Shortcodes - `[distance]` - Rounding attributes added (`rounding` and `rounding_precision`).
* Feature - Shortcodes - `[distance]` - Min and max distance attributes added (`min_distance` and `max_distance`).
* Dev - Code refactoring.

= 1.4.3 - 03/10/2019 =
* Dev - General - Frontend Settings - Add to zero cost - Admin settings descriptions updated.
* WC tested up to: 3.7.

= 1.4.2 - 14/06/2019 =
* Dev - Shipping class costs - "Limits calculation" option added.
* Dev - Admin settings descriptions updated ("Free shipping min amount").
* Dev - Minor code refactoring.

= 1.4.1 - 25/05/2019 =
* Dev - General - Frontend Settings - Trigger checkout update - Now triggering checkout update only on `billing_` and `shipping_` input change.
* Dev - General - Frontend Settings - Add to zero cost - Admin settings descriptions updated.

= 1.4.0 - 24/05/2019 =
* Feature - Shortcodes - `[costs_table]` - `table_format` attribute added (defaults to `min`; other possible value: `range`) (and `default_cost` attribute added).
* Feature - Shortcodes - `[distance]` - Miles (`mi`) option added to the `units` attribute (same applies to `[costs_table prop="distance" ...]`).
* Dev - General - Frontend Settings - "Trigger checkout update" option added.
* Dev - Shortcodes - `[distance]` - `default` attribute renamed to `default_distance`.
* Dev - `WC_Shipping_Alg_Custom` - `alg_wc_custom_shipping_methods_add_rate` filter added.
* Dev - `WC_Shipping_Alg_Custom` - `evaluate_cost()` function visibility changed from `protected` to `public`.
* WC tested up to: 3.6.
* Tested up to: 5.2.

= 1.3.1 - 09/02/2019 =
* Feature - `[costs_table]` - `cost` property added (i.e. `[costs_table prop="cost" ...]`).
* Feature - "Free shipping minimum order amount" option added.
* Dev - `[costs_table]` - Code refactoring.
* Dev - Admin settings restyled.

= 1.3.0 - 03/12/2018 =
* Feature - "Min cost limit" and "Max cost limit" options added.
* Feature - `[distance]` shortcode added.
* Feature - `distance` `prop` added to the `[costs_table]` shortcode (i.e. `[costs_table prop="distance"]`).
* Dev - Method settings descriptions updated.
* Dev - `alg_wc_custom_shipping_methods_evaluate_cost_sum` and `alg_wc_custom_shipping_methods_evaluate_cost_sum_evaluated` filters added.

= 1.2.1 - 14/11/2018 =
* Feature - "Replace zero cost" options added.
* Dev - Code refactoring.

= 1.2.0 - 18/09/2018 =
* Feature - "Method icon" and "Method description" options added.
* Fix - `[costs_table]` shortcode fixed.
* Dev - Admin settings restyled.

= 1.1.0 - 14/09/2018 =
* Feature - Availability - "Min cost", "Max cost", "Min volume", "Max volume", "Min quantity", "Max quantity" options added.
* Fix - Core - Checking if product has dimensions before calling `get_height()`, `get_width()` and `get_length()` in `get_products_volume()`.
* Fix - Core - Checking if product has weight before calling `get_weight()` in `get_products_weight()`.
* Dev - Default "Admin title" and "Method title" values updated.
* Dev - Minor admin settings restyling.
* Dev - Code refactoring.
* Dev - POT file added.
* Dev - Plugin description in readme.txt updated.
* Dev - Plugin URI updated.

= 1.0.0 - 09/05/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
