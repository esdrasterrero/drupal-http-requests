# Setting up the project
#### Table of contents

- [Setup](#setup)
  - [Requirements for running the project](#project-requirements)
  - [Install DDEV](#install-ddev)
- [Starting the project](#starting-the-project)
  - [Spin up the project](#spin-up-project)
  - [Import the provided sample database (optional)](#import-database)
- [Launching the project](#launching-the-project)
  - [Launch the project with the provided sample database](#launching-with-sample-db)
  - [Launch the project using the `drush site:install` command](#launch-with-drush-command)
- [Using the available blocks](#using-blocks)

<a name="setup"></a>
## Setup
<a name="project-requirements"></a>
### Requirements for running the project
- A [Docker](https://ddev.readthedocs.io/en/stable/users/install/docker-installation/) provider
- [DDEV installation](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
- A terminal application
- [Composer](https://getcomposer.org/download/)
<a name="install-ddev"></a>
### Install DDEV
DDEV can run on a variety of operating systems: [macOS](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/#__tabbed_1_1), [Linux](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/#__tabbed_1_2) & [Windows](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/#__tabbed_1_3)

On a mac, you may use the following example to install DDEV:

> [Homebrew](https://brew.sh/) is the easiest and most reliable way to install and upgrade DDEV.

- Source: [DDEV Documentation](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation)

#### Installing DDEV with Homebrew
```shell
# Install DDEV
brew install ddev/ddev/ddev

# Initialize mkcert
mkcert -install
```
<a name="starting-the-project"></a>
## Starting the project
<a name="spin-up-project"></a>
### Spin up the project
Within the terminal, navigate to the project's directory and run the following commands:
```shell
# Run ddev start to spin up the project.
ddev start

# Install the necessary packages with composer.
ddev composer install
```
<a name="import-database"></a>
### Import the provided sample database (optional)
```shell
ddev import-db --file=.ddev/resources/db.sql.gz
```
<a name="launching-the-project"></a>
## Launching the project
<a name="launching-with-sample-db"></a>
### Launch the project with the provided sample database
To launch the project in your browser, run the following command in your browser:
```shell
# Launch a browser with the current site
ddev launch

# Use the one-time link (CTRL/CMD + Click) from the command below to sign into the backend as an administrator.
ddev drush uli
```
<a name="launch-with-drush-command"></a>
### Launch the project using the `drush site:install` command
If the project was launched without previously [importing the sample database](README.md:57), you may install Drupal using Drupal's interactive installer, or run the site installation command in your terminal with drush.

```shell
# Install drupal with an active admin account.
ddev drush site:install --account-name=admin --account-pass=admin -y

# Use the one-time link (CTRL/CMD + Click) from the command below to edit your admin account details.
ddev drush uli
```

After the installation is complete, run the following commands to install the necessary modules[^1].
```shell
ddev drush en api_connector,beer_widget,cat_widgets
```

[^1]: If you've imported the sample database, then these modules are already installed.
<a name="using-blocks"></a>
## Using the available blocks

This Drupal installation has a few blocks that can be placed on a page.

| Block name                | Description                                                              |
|---------------------------|--------------------------------------------------------------------------|
| Punk API Random Beer Block | Loads a random beer upon each page reload                                |
| Punk API Beer Finder Block | Add a text field to search for recommended beers for a specific dish.    |
| Cat API Random Cat Block  | Loads a random cat image upon each page reload. Includes voting buttons. |
| Cat API finder block  | Search for cats by breed.                                                |
