Pointless
=========

### Description

Static blog generator

### Requirement

* PHP 5.3+

### Installation

#### Clone Pointless from github

	git clone git://github.com/scarwu/Pointless.git
	
#### Add this line to `.bashrc` or `.zshrc` file
	
	export PATH=$PATH:/path/to/Pointless/Bin/

#### Initialize Pointless

	./init.sh
	
Default Folder on /path/to/Pointless/Blog
and Static Page Folder on /path/to/Pointless/Blog/Public

### Custom

#### Blog Path
If you want to custom your blog output path, do this ...

	cp Config.default.php Config.php
	vi Config.php

and modify line 5.

	define('BLOG', '/path/to/your/blog/folder');

#### Blog UI
If you want to custom blog css layout, javascript file, or something else ...

	cp -a UI /path/to/path/to/your/blog/folder
	