# [osTicket](https://osticket.com) Plugin: Ticket Status Auto-changer
Ticket Status Auto-changer is a plugin for osTicket that allows you to automatically change the status of a ticket when a customer replies.

## Compatibility
Version 2.x is compatible with osTicket 1.17 and PHP 8.

Version 1.0 is compatible with osTicket 1.11-1.15 and PHP 7.

## Installation
1. Download from [Releases](https://github.com/dhimelick/osticket-plugin-statusautochange/releases), unzip, and copy the folder containing the PHP files into `/include/plugins/`.
2. In the admin panel, go to **Manage > Plugins**.
3. Click **Add New Plugin**.
4. Click **Install** next to "Ticket Status Auto-changer".

## Configuration
This plugin has only one setting: the status which you'd like a ticket to become when a client replies. You can choose any status that is an "open" or "closed" state.

Once you've added a status, save your changes, then enable the plugin.

## Credits
- https://github.com/clonemeagain/osticket-slack was immensely helpful as a starting point and for the `getTicket` method.
- https://github.com/poctob/OSTEquipmentPlugin/wiki/Plugin-Development-Introduction
