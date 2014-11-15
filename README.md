# Musicwhore.org Artist Connector

## Overview

Musicwhore.org Artist Connector connects the Musicwhore.org artist database with content imported from Movable Type.

This plugin powers the artist directory featured in the [Musicwhore.org Archive](http://archive.musicwhore.org/).

Musicwhore.org Artist Connector queries the Musicwhore.org artist, which is then displayed in the [Musicwhore.org Archive theme](https://bitbucket.org/NemesisVex/musicwhore.org-archive-theme-for-wordpress).

## Installation

Install the plugin directory under ``wp-content/plugins`` or ``wp-content/mu-plugins``.

## Configuration

After installation, go to Settings / Musicwhore Artist Connector and fill in the fields to connect to the Observant Records database.

* **Database host**: The name where the Observant Records database is hosted.
* **Database name**: The name of the Observant Records database on the server.
* **Database user**: The name of a user with credentials to read to the Observant Records database.
* **Database password**: The password of the user.

Fill in additional fields for connecting to the Amazon Product Marketing API.

* **Access key**: Your Amazon Web Services Access Key.
* **Affiliate ID**: Enter your affiliate IDs for the US, UK and Japan.

For queries to the Amazon Product Marketing API to work, you must define a constant named ```MUSICWHORE_AWS_SECRET_KEY``` with your Amazon Web Servers Secret Key in ```wp-config.php```.

```define( MUSICWHORE_AWS_SECRET_KEY, 'Amazon secret key goes here');```

## Usage

After the plugin has been enabled and a database connection stored, the Musicwhore.org Artist Connector adds a section in your post editing screen for providing Musicwhore.org Artist meta data.