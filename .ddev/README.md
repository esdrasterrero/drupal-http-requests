# Setting up the project
#### Table of contents

- [Setup](./README.md:15)
  - [Requirements for running the project](README.md:16)
  - [Install DDEV](README.md:22)
- [Starting the project](README.md:40)
  - [Spin up the project](README.md:41)
  - [Import the provided sample database (optional)](README.md:51)
- [Launching the project](README.md:56)
  - [Launch the project with the provided sample database](README.md:57)
  - [Launch the project using the `drush site:install` command](README.md:67)
- [Using the available blocks](README.md:85)

## Setup
### Requirements for running the project
- A [Docker](https://ddev.readthedocs.io/en/stable/users/install/docker-installation/) provider
- [DDEV installation](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
- A terminal application
- [Composer](https://getcomposer.org/download/)

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

## Starting the project
### Spin up the project
Within the terminal, navigate to the project's directory and run the following commands:
```shell
# Run ddev start to spin up the project.
ddev start

# Install the necessary packages with composer.
ddev composer install
```

### Import the provided sample database (optional)
```shell
ddev import-db --file=.ddev/resources/db.sql.gz
```

## Launching the project
### Launch the project with the provided sample database
To launch the project in your browser, run the following command in your browser:
```shell
# Launch a browser with the current site
ddev launch

# Use the one-time link (CTRL/CMD + Click) from the command below to sign into the backend as an administrator.
ddev drush uli
```

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

## Using the available blocks

This Drupal installation has a few blocks that can be placed on a page.

| Block name                | Description                                                              |
|---------------------------|--------------------------------------------------------------------------|
| Punk API Random Beer Block | Loads a random beer upon each page reload                                |
| Punk API Beer Finder Block | Add a text field to search for recommended beers for a specific dish.    |
| Cat API Random Cat Block  | Loads a random cat image upon each page reload. Includes voting buttons. |
| Cat API finder block  | Search for cats by breed.                                                |
