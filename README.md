# [osTicket](https://osticket.com) Plugin: Ticket Status Auto-changer

Ticket Status Auto-changer is a plugin for osTicket that allows you to automatically change the status of a ticket when a customer replies.

## Compatibility

Version 2.1 is compatible with osTicket 1.17+ and PHP 8+.

Version 1.0 is compatible with osTicket 1.11-1.15 and PHP 7.

## Installation

1. Download from [Releases](https://github.com/dhimelick/osticket-plugin-statusautochange/releases), unzip, and copy the folder containing the PHP files into `/include/plugins/`.
2. In the admin panel, go to **Manage > Plugins**.
3. Click **Add New Plugin**.
4. Click **Install** next to "Ticket Status Auto-changer".

## Configuration

This plugin has two settings:

- **Change status if original status is**: This setting determines the original status of a ticket that is required for this plugin to change the status. It defaults to any status.
- **Change status to**: This is the status that a ticket should become after a customer replies. It defaults to the first status in your osTicket instance by ID.

Once you've selected your desired statuses, save your changes, then enable the instance and the plugin.

### Plugin Instances (osTicket 1.18+)

Version 2.1 of this plugin adds proper support for the new plugin instances feature of osTicket 1.18. This means you can, e.g., configure one instance of this plugin to to change tickets from Status 1 to Status 2 and another instance from Status 3 to Status 4. Note that plugin instances are processed in sequence starting from the first instance you created, so it's possible to change a ticket's status multiple times depending on your configuration. (Is this useful? Not that I can tell, but it does work as you'd expect!)

## Credits

- https://github.com/clonemeagain/osticket-slack was immensely helpful as a starting point and for the `getTicket` method.
- https://github.com/poctob/OSTEquipmentPlugin/wiki/Plugin-Development-Introduction
