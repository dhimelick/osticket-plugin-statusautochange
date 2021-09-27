# [osTicket](https://osticket.com) Plugin: Ticket Status Auto-changer
Ticket Status Auto-changer is a plugin for osTicket that allows you to automatically change the status of a ticket when a client replies. It has been tested and works on osTicket versions 1.11 through 1.14. It may work on later versions but has not been tested.

## Installation
1. Download as a zip and extract into `/include/plugins/statusautochange`.
2. In the admin panel, go to **Manage > Plugins**.
3. Click **Add New Plugin**.
4. Click **Install** next to "Ticket Status Auto-changer."

## Configuration
This plugin has only one setting: the status which you'd like a ticket to become when a client replies. You can choose any status that is an "open" or "closed" state.

Once you've added a status, save your changes, then enable the plugin.

## Credits
- https://github.com/clonemeagain/osticket-slack was immensely helpful as a starting point and for the `getTicket` method.
- https://github.com/poctob/OSTEquipmentPlugin/wiki/Plugin-Development-Introduction
