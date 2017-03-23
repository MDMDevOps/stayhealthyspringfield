# Project Repo for Venture

Project repository for syncing local, and staging work for venture apartments

## Usefule Links
- **Staging:** [http://mdmserver.us/venture](http://mdmserver.us/venture)
- **Github:** [https://github.com/MDMDevOps/venture](https://github.com/MDMDevOps/venture)



### Important Notes:
1. **Never PUSH without first PULLING
2. *Never PUSH without first PULLING*
3. **Never PUSH without first PULLING**

...in other words

#### Always PULL before you PUSH

<hr>

## Instruction

1. Install WordPress locally using the latest version
2. Clone and configure repository
3. Configure DB Sync
4. Pull Content => Push Code

### 1. Install Wordpress

**Using VV**

```bash
	vv create
```
**Using CURL**

```bash
	wget http://wordpress.org/latest.tar.gz
	tar xfz latest.tar.gz
	mv wordpress/* ./
	rmdir ./wordpress/
	rm -f latest.tar.gz
```

**Manually**
- Navigate to (https://wordpress.org/download/)[https://wordpress.org/download/] and download the latest version
- Unpack into desired working directory

After you've downloaded Wordpress, create a database (if not using VV or wp-cli). Finally install it. For specific installation instructions, google it...or whatever.

### 2. Clone and configure repository

```bash
git clone git@github.com:MDMDevOps/venture.git
```

Open up **.git/config** and add the block:

```bash
	[merge "theirs"]
	    name = "Keep theirs merge"
	    driver = false
```

This means we keep the repo version of compiled files, and recompile manually prior to pushing


### 3. Configure DB Sync

- Activate the migrate DB plugin, and migrate media plugin
- Go to staging server and grab API key from migrate DB settings
- Paste settings into local plugin, and pull content from staging. Make sure to include media files

### 4. Do work. Push CODE to staging, and Pull CONTENT from staging
