=== GPDF From ===
Contributors: opcodespace
Donate link: https://linkpdfform.com/
Tags: gravity form, pdftk, pdf-form, pdf-form-filler, pdf, form
Requires at least: 5.0
Tested up to: 5.0
Stable tag: 4.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: 

With GPDF From plugin you can filling pdf form from gravity form. Very simple configuration, need not any specific server.

== Description ==

GPDF form plugin depends on the *gravity form*. So you must need https://www.gravityforms.com/. Please make sure you have installed gravity form.

Filling pdf form depends on pdftk library which is not available all sever by default. To get rid this complexity, you should upload the pdf form here [linkpdfform](https://linkpdfform.com/). 

This plugin supports only *adobe acrobat static form*, does not support xfa dynamic pdf form. If you need any solution for xfa dynamic pdf form, please contact here: [contact-us](https://linkpdfform.com/contact-us/)

We have also API but not released publicly. If you need outside gravity form, please contact us: [contact-us](https://linkpdfform.com/contact-us/)

= Limitation =
Right now only one templete supports with one form. If you want more feature we are happy to enahnce the feature as your need. Please contact with us. 

== Installation ==

Please follow the instrcution. This plugins rely on gravity form. So make sure you have enabled gravity form. 

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Forms->Settings->GPDF Form to configure the plugin
4. To Get API KEY, you should register here https://linkpdfform.com/login/
5. Now go on my-account->Your API Key->Generate API Key

= Uploading PDF Form Templates =
1. At first you have to upload static pdf form on linkpdfform.com. Use My Account -> Templates

= Mapping pdf form fields with gravity form fields =
1. Go on your wp dashboard
2. GPDF Form -> Add New -> Insert Tilte -> Update
3. Select Gravity form 
4. Select templates and then map

= View Generated PDF =
1. ON gravity form entries you see view pdf tab. 



== Frequently Asked Questions ==

= How does it work? =
Gpdf form is depending on linkpdfform server. After completing task system pass to your website. We don't save your data or filled pdf form. Just we save your template on our server to keep process faster.

= Does it work in shared server? =

Yes. No special requirements.

= Is it secure =

Yes, our server is secure. We don't save anything except pdf template. Make sure you have ssl with your website.

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png
3. /assets/screenshot-3.png
4. /assets/screenshot-4.png
5. /assets/screenshot-5.png
6. /assets/screenshot-6.png

== Changelog ==

= 0.1 =
* Integrated linkpdf form api to gpdf form plugin


== Upgrade Notice ==

= 0.1 =
* You need graty form and register with https://linkpdfform.com