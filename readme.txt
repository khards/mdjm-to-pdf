=== MDJM to PDF ===
Contributors: mikeyhoward1977
Tags: DJ, Mobile DJ, DJ Planning, Event Planning, CRM, Event Planner, DJ Event Planner, DJ Agency, DJ Tool, Playlist Management, Contact Forms, Mobile Disco, Disco, Event Management, DJ Manager, MDJM Event Management, DJ Management
Requires at least: 4.1
Tested up to: 4.6
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://mdjm.co.uk/support-our-work/

MDJM to PDF compliments the MDJM Event Management for WordPress plugin by enabling exports of Event documentation to PDF

== Description ==

MDJM to PDF compliments the MDJM Event Management for WordPress plugin enabling you and your clients to convert Contracts and Quotes to PDF format for printing, saving or emailing.

Simple shortcodes enable you to easily add links to your client facing pages allowing them to print or download the relevant document.

* [mdjm-pdf-print text="Print"]
* [mdjm-pdf-download text="Download"]

Shortcodes can be used within Contract and Online Quote pages.

Additionally, you can override the default MDJM Event Management template settings so rather than sending a text based fully customised email, if you prefer you can configure MDJM to PDF 
to email a simple text based message and attach a selected template as a PDF file.

All attachments sent via email within the MDJM Event Management plugin with the MDJM to PDF plugin installed, are saved to your webserver. With email tracking enabled, you can view these emails, including their attachments, and even see if your client has opened it

== Installation ==
<strong>Note</strong>: The MDJM to PDF plugin requires the MDJM Event Management plugin to be installed, activated and at least at version 1.2.6

<strong>Automated Installation</strong>

1. Login to your WordPress administration screen and select "Plugins" -> "Add New" from the menu
1. Enter "MDJM to PDF" into the Search Plugins text box and hit Enter
1. Click "Install Now" within the MDJM to PDF plugin box
1. Activate the plugin once installation is completed

<strong>Manual Installation</strong>

Once you have downloaded the plugin zip file, follow these simple instructions to get going;

1. Login to your WordPress administration screen and select the "Plugins" -> "Add New" from the menu
1. Select "Upload Plugin" from the top of the main page
1. Click "Choose File" and select the mdjm-to-pdf.zip file you downloaded
1. Click "Install Now"
1. Once installation has finished, select "Activate Plugin"

== Frequently Asked Questions ==

= Is any support provided? =

Support can be obtained via our online [Support Forums](http://www.mydjplanner.co.uk/support/ "MDJM Support Forums") at or via our [Facebook User Group](https://www.facebook.com/groups/mobiledjmanager "MDJM Facebook User Group").

= Is there a Pro version with additional features? =

Premium addons are available to further enhance the MDJM Event Management plugin at http://mdjm.co.uk

== Screenshots ==


== Licensing ==

== Changelog ==

= 1.1 =

* **Tweak**: Updated shortcodes to use `MDJM_Event` class
* **Tweak**: Use `mdjm_do_content_tags()`
* **Tweak**: Updated coding standards
* **Tweak**: Added version check for MDJM

= 1.0 =

**Released 12th May, 2016**

* Tweak: Use MDJM's new content tags
* Tweak: Use MDJM's new filters for setting subject, content, attachments
* Tweak: Use MDJM's new settings API
* Tweak: Use MDJM's new singleton class
* Tweak: Compatibility for MDJM 1.3

= 0.3 =
**Released 26th November, 2015**

* General: mdjm_shortcode_filter_pairs now accepts an additional arg
* General: Set debugging to false
* Bug fix: Use function_exists when referring to MDJM functions to avoid fatal errors when MDJM is deactivated
* Bug Fix: If PHP E_STRICT standards are in place, error being logged and in rare instances, white screen

= 0.2 =
**Released 18th November, 2015**
* Bug Fix: Contracts could not be sent as attachments via the Comms feature
* General: Added update procedures
* General: Moved to GitHub

= 0.1 =
<strong>Released 29th October, 2015</strong>
Initial Release!