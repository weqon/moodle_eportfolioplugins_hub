# ePortfolio #

The “ePortfolio Hub” in Moodle provides a way to publish your own ePortfolios
within the Moodle instance for internal or external use.

**Note: This plugin was published as MATURITY_BETA.
If you encounter any issues using this plugin please report them to:
<https://github.com/weqon/moodle_eportfolioplugins_hub/issues>**

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/eportfolio/hub}

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

### After installation ###
New settings will be added to the existing settings page for the local_eportfolio plugin. 
Enable the Hub to use the new features provided.

## Relase notes ##

### Moodle 4.5 ###

**Version 0.1.0**

- Initial Beta-Release

## License ##

2026 weQon UG <support@weqon.net>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
