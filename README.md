# Pointless

A Static Blog Generator with PHP

## Getting Started

### Requirement

* PHP 8.4+

### Install

~~~
wget https://raw.github.com/scarwu/Pointless/master/bin/poi -O /tmp/poi
chmod +x /tmp/poi
sudo mv /tmp/poi /usr/local/bin/poi
~~~

### Update

~~~
sudo poi update
~~~

## Usage

~~~
poi blog init <path?>       - Init blog at the given path (defaults to current directory)
poi blog build              - Build the static blog
poi blog deploy             - Deploy the built blog
poi blog backup             - Backup the blog
poi blog config             - Show blog configuration

poi post                    - Show post status
poi post add                - Add a new post
poi post edit               - Edit an existing post
poi post delete             - Delete a post

poi theme                   - Show theme status
poi theme install           - Install a theme
poi theme uninstall         - Uninstall a theme

poi server                  - Show server status
poi server start            - Start the built-in web server
poi server stop             - Stop the built-in web server
~~~

## Development

### Install Packages

~~~
./scripts/setup.sh
~~~

### Build

~~~
php scripts/build.php
~~~

## Docker

~~~
docker pull scarwu/pointless
~~~

Or use the wrapper script:

~~~
./docker/wrapper.sh <command>
~~~

## Demo

[ScarShow](https://scar.tw)
