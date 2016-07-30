<?php

namespace Davaxi;

/**
 * Class Event_Template
 * @package Davaxi
 */
class Google_Event_Template
{

    /**
     * Url for generated event
     */
    const URL = 'https://www.google.com/calendar/event';

    /**
     * Title of event
     * @var string
     */
    protected $title = '';

    /**
     * Description of event
     * @var string
     */
    protected $description = '';

    /**
     * Timestamp of start date in specified TimeZone
     * @var integer
     */
    protected $startDate = 0;

    /**
     * Timestamp of end date in specified TimeZone
     * @var integer
     */
    protected $endDate = 0;

    /**
     * Timezone of event
     * @var string
     */
    protected $timeZone = '';

    /**
     * Location of event
     * @var string
     */
    protected $location = '';

    /**
     * Show event as busy (true) or available (false)
     * @var bool
     */
    protected $hasBusy = false;

    /**
     * This is not covered by Google help but is an optional parameter in order to add an event to a shared calendar
     * rather than a user's default
     * @var string
     */
    protected $ownerEmail = '';

    /**
     * Guests emails
     * @var array
     */
    protected $guestsEmails = array();

    /**
     * The documentation says this is to identify the website or event source.
     * It says it’s required, but excluding it seems to make no difference.
     * @var string
     */
    protected $propertyName = '';

    /**
     * The documentation says this is to identify the website or event source.
     * It says it’s required, but excluding it seems to make no difference.
     * @var string
     */
    protected $propertyWebsite = '';

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $startDate Start date in TimeZone
     */
    public function setStartDate($startDate)
    {
        $startDateEpoch = strtotime($startDate);
        if (!$startDateEpoch) {
            throw new \InvalidArgumentException('Invalid representation of start date: ' . $startDate);
        }
        $this->startDate = $startDateEpoch;
    }

    /**
     * @param string $endDate End date in TimeZone
     */
    public function setEndDate($endDate)
    {
        $endDateEpoch = strtotime($endDate);
        if (!$endDateEpoch) {
            throw new \InvalidArgumentException('Invalid representation of end date: ' . $endDate);
        }
        $this->endDate = $endDateEpoch;
    }

    /**
     * @param string $timeZone
     */
    public function setTimeZone($timeZone)
    {
        if (!in_array($timeZone, timezone_identifiers_list())) {
            throw new \InvalidArgumentException('Invalid timeZone: ' . $timeZone);
        }
        $this->timeZone = $timeZone;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Show event as busy
     */
    public function eventHasBusy()
    {
        $this->hasBusy = true;
    }

    /**
     * @param string $emailOwner
     */
    public function setOwnerEmail($emailOwner)
    {
        if (!static::checkAcceptedEmail($emailOwner)) {
            throw new \InvalidArgumentException('Invalid owner email: ' . $emailOwner);
        }
        $this->ownerEmail = $emailOwner;
    }

    /**
     * @param string $guestEmail
     */
    public function addGuestEmail($guestEmail)
    {
        if (!static::checkAcceptedEmail($guestEmail)) {
            throw new \InvalidArgumentException('Invalid guest email: ' . $guestEmail);
        }
        $this->guestsEmails[] = $guestEmail;
    }

    /**
     * @param string $propertyName
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

    /**
     * @param string $propertyWebsite
     */
    public function setPropertyWebsite($propertyWebsite)
    {
        if (filter_var($propertyWebsite, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('Invalid property website: ' . $propertyWebsite);
        }
        $this->propertyWebsite = $propertyWebsite;
    }

    /**
     * Generate URL
     * @return string
     */
    public function getUrl()
    {
        if (!$this->timeZone) {
            throw new \LogicException('Not defined event timeZone');
        }
        if (!$this->title) {
            throw new \LogicException('Not defined event title');
        }
        if (!$this->startDate) {
            throw new \LogicException('Not defined event startDate');
        }
        if (!$this->endDate) {
            throw new \LogicException('Not defined event endDate');
        }
        if ($this->startDate > $this->endDate) {
            throw new \InvalidArgumentException('Invalid dates: startDate > endDate');
        }

        $params = array(
            'action' => 'TEMPLATE',
            'text' => $this->title,
            'details' => $this->description,
            'dates' =>  sprintf('%s/%s',
                gmdate('Ymd\\THi00\Z', $this->startDate),
                gmdate('Ymd\\THi00\Z', $this->endDate)
            ),
            'ctz' => $this->timeZone,
            'location' => $this->location,
            'trp' => $this->hasBusy ? 'true' : 'false',
            'sprop' => array(),
        );
        if ($this->propertyName) {
            $params['sprop'][] = sprintf('name:%s', $this->propertyName);
        }
        if ($this->propertyWebsite) {
            $params['sprop'][] = sprintf('website:%s', $this->propertyWebsite);
        }
        if ($this->ownerEmail) {
            $params['src'] = $this->ownerEmail;
        }
        if ($this->guestsEmails) {
            $params['add'] = array();
            foreach ($this->guestsEmails as $guestEmail) {
                $params['add'][] = $guestEmail;
            }
        }
        $query = http_build_query($params, null, '&');
        $query = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $query);
        return sprintf('%s?%s', static::URL, $query);
    }

    /**
     * Check if email has accepted by Google Calendar
     * Accepted:
     * - username@domain.fr
     * - test@localhost
     * @param $email
     * @return bool
     */
    protected static function checkAcceptedEmail($email)
    {
        $result = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        return $result || preg_match('/^[a-zA-Z0-9_\-\.\+]+@[a-zA-Z0-9\-]+$/', $email);
    }

}