# Google Event Template

PHP Class to generate Google Calendar Event link

[![Build Status](https://travis-ci.org/davaxi/Google_Event_Template.svg)](https://travis-ci.org/davaxi/Google_Event_Template)
[![Latest Stable Version](https://poser.pugx.org/davaxi/google_event_template/v/stable)](https://packagist.org/packages/davaxi/google_event_template) 
[![Total Downloads](https://poser.pugx.org/davaxi/google_event_template/downloads)](https://packagist.org/packages/davaxi/google_event_template) 
[![Latest Unstable Version](https://poser.pugx.org/davaxi/google_event_template/v/unstable)](https://packagist.org/packages/davaxi/google_event_template) 
[![License](https://poser.pugx.org/davaxi/google_event_template/license)](https://packagist.org/packages/davaxi/google_event_template)

## Installation

This page contains information about installing the Library for PHP.

### Requirements

- PHP version 5.2.0 or greater (including PHP 7)

### Obtaining the client library

There are two options for obtaining the files for the client library.

#### Using Composer

You can install the library by adding it as a dependency to your composer.json.

```
  "require": {
    "davaxi/google_event_template": "^1.0"
  }
```

#### Cloning from GitHub

The library is available on [GitHub](https://github.com/davaxi/Google_Event_Template). You can clone it into a local repository with the git clone command.

```
git clone https://github.com/davaxi/Google_Event_Template.git
```

### What to do with the files

After obtaining the files, ensure they are available to your code. If you're using Composer, this is handled for you automatically. If not, you will need to add the `autoload.php` file inside the client library.

```
require '/path/to/google_event_template/folder/autoload.php';
```

## Usage

```php
<?php

require '/path/to/google_event_template/folder/autoload.php';

$event = new Davaxi\Google_Event_Template();
$event->setTimeZone('Europe/Paris');
$event->setStartDate('2016-10-01 12:00:00');
$event->setEndDate('2016-10-01 14:00:00');
$event->setTitle('My Event Title');

$url = $event->getUrl();

printf('<a href="%s" target="_blank">Add event to Google Calendar</a>', $url);
```

## Documentation

```php
<?php

$event = new Davaxi\Google_Event_Template();

// Required fields //

// Event title
$event->setTitle('My Event Title');

// Event timeZone
$event->setTimeZone('Europe/Paris');

// Event start date in specified TimeZone
$event->setStartDate('2016-10-01 12:00:00');

// Event end date in specified TimeZone
$event->setEndDate('2016-10-01 14:00:00');

// Optional fields //
// Event description
$event->setDescription('My Event Description');

// Event Location
$event->setLocation('My Location');

// Set event as busy (true) (default available (false))
$event->eventHasBusy();

// This is not covered by Google help but is an optional 
// parameter in order to add an event to a shared calendar 
// rather than a user's default
$event->setOwnerEmail('root@domain.com');
// or 
$event->setOwnerEmail('root@localhost');
 
// Add event Guest email
$event->addGuestEmail('guest@domain.com');
// or 
$event->addGuestEmail('guest@localhost');

// The documentation says this is to identify the event source.
// It seems to make no difference.
$event->setPropertyName('My Society');

// The documentation says this is to identify the website source.
// It seems to make no difference.
$event->setPropertyWebsite('https://www.mywebsite.com');

// Get Url for add Event
$event->getUrl();
```