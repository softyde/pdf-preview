# Pdf Preview Plugin

The **Pdf Preview** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). It automatically creates preview images (.png) of uploaded PDF files.

## Requirements

This plugin requires a running version of the ImageMagick binaries in /usr/bin.

The current implementation does not work on Windows installations.

## Installation

Installing the Pdf Preview plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install pdf-preview

This will install the Pdf Preview plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/pdf-preview`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `pdf-preview`. You can find these files on [GitHub](https://github.com//grav-plugin-pdf-preview) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/pdf-preview
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com//grav-plugin-pdf-preview/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/pdf-preview/pdf-preview.yaml` to `user/config/plugins/pdf-preview.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named pdf-preview.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

Just drop the files to your /plugins directory.

The plugin creates a 200x200 pixel preview image of the first page for each PDF file (identified by MIME type) when saving a page.
The file is stored in the folder of the respective page and has the filename "__preview.[original name].png".

(The theme I use uses images whose names start with two underscores only as previews in lists and does not display them on the respective detail page).

Theoretically, you could also perform the action directly when uploading a PDF file (onAdminAfterAddMedia). I was not quite clear if and how this has influence on Grav's caching. 
The current implementation is a bit of a kludge.

For security reasons, all PDF files whose names are somehow suspicious are ignored during processing.

## To Do

- [ ] Future plans, if any
- [ ] Take over world domination
