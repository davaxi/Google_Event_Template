<?php

use Davaxi\Google_Event_Template as Google_Event_Template;

class Google_Event_TemplateMockup extends Google_Event_Template
{
    public function getAttribute($attribute)
    {
        return $this->$attribute;
    }
}

class Google_Event_TemplateTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Google_Event_TemplateMockup
     */
    protected $template;

    public function setUp()
    {
        parent::setUp();
        $this->template = new Google_Event_TemplateMockup();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->template);
    }

    public function testSetTitle()
    {
        $value = 'My Personal Title';
        $this->template->setTitle($value);
        $this->assertEquals($value, $this->template->getAttribute('title'));
    }

    public function testSetDescription()
    {
        $value = 'My Personal Description';
        $this->template->setDescription($value);
        $this->assertEquals($value, $this->template->getAttribute('description'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStartDateInvalidString()
    {
        $this->template->setStartDate('invalid string');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStartDateInvalidDateString()
    {
        $this->template->setStartDate('2012-24-14');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStartDateInvalidNumeric()
    {
        $this->template->setStartDate(10000);
    }

    public function testSetStartDateAbsoluteDate()
    {
        $value = '2016-10-12 10:00:00';
        $this->template->setStartDate($value);
        $this->assertEquals(strtotime($value), $this->template->getAttribute('startDate'));
    }

    public function testSetStartDateRelativeDate()
    {
        $value = 'tomorrow 00:00:00';
        $this->template->setStartDate($value);
        $this->assertEquals(strtotime($value), $this->template->getAttribute('startDate'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetEndDateInvalidString()
    {
        $this->template->setEndDate('invalid string');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetEndDateInvalidDateString()
    {
        $this->template->setEndDate('2012-24-14');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetEndDateInvalidNumeric()
    {
        $this->template->setEndDate(10000);
    }

    public function testSetEndDateAbsoluteDate()
    {
        $value = '2016-10-12 10:00:00';
        $this->template->setEndDate($value);
        $this->assertEquals(strtotime($value), $this->template->getAttribute('endDate'));
    }

    public function testSetEndDateRelativeDate()
    {
        $value = 'tomorrow 00:00:00';
        $this->template->setEndDate($value);
        $this->assertEquals(strtotime($value), $this->template->getAttribute('endDate'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTimeZoneInvalid()
    {
        $this->template->setTimeZone('Invalid time zone string');
    }

    public function testSetTimeZone()
    {
        $value = 'Europe/Paris';
        $this->template->setTimeZone($value);
        $this->assertEquals($value, $this->template->getAttribute('timeZone'));
    }

    public function testSetLocation()
    {
        $value = 'My private address';
        $this->template->setLocation($value);
        $this->assertEquals($value, $this->template->getAttribute('location'));
    }

    public function testEventHasBusy()
    {
        $this->assertFalse($this->template->getAttribute('hasBusy'));
        $this->template->eventHasBusy();
        $this->assertTrue($this->template->getAttribute('hasBusy'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetOwnerEmailInvalidString()
    {
        $this->template->setOwnerEmail('invalid email');
    }

    public function testSetOwnerEmailLocal()
    {
        $value = 'root@localhost';
        $this->template->setOwnerEmail($value);
        $this->assertEquals($value, $this->template->getAttribute('ownerEmail'));
    }

    public function testSetOwnerEmailPublic()
    {
        $value = 'root@domain.fr';
        $this->template->setOwnerEmail($value);
        $this->assertEquals($value, $this->template->getAttribute('ownerEmail'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddGuestEmailInvalidString()
    {
        $this->template->addGuestEmail('invalid email');
    }

    public function testAddGuestEmailLocal()
    {
        $value = 'root@localhost';
        $this->template->addGuestEmail($value);
        $guestsEmails = $this->template->getAttribute('guestsEmails');
        $this->assertInternalType('array', $guestsEmails);
        $this->assertCount(1, $guestsEmails);
        $this->assertArrayHasKey(0, $guestsEmails);
        $this->assertEquals($value, $guestsEmails[0]);
    }

    public function testAddGuestEmailPublic()
    {
        $value = 'root@domain.fr';
        $this->template->addGuestEmail($value);
        $guestsEmails = $this->template->getAttribute('guestsEmails');
        $this->assertInternalType('array', $guestsEmails);
        $this->assertCount(1, $guestsEmails);
        $this->assertArrayHasKey(0, $guestsEmails);
        $this->assertEquals($value, $guestsEmails[0]);
    }

    public function testAddGuestEmailMultiple()
    {
        $value = 'root@domain.fr';
        $value2 = 'root@localhost';
        $this->template->addGuestEmail($value);
        $this->template->addGuestEmail($value2);
        $guestsEmails = $this->template->getAttribute('guestsEmails');
        $this->assertInternalType('array', $guestsEmails);
        $this->assertCount(2, $guestsEmails);
        $this->assertArrayHasKey(0, $guestsEmails);
        $this->assertEquals($value, $guestsEmails[0]);
        $this->assertArrayHasKey(1, $guestsEmails);
        $this->assertEquals($value2, $guestsEmails[1]);
    }

    public function testSetPropertyName()
    {
        $value = 'My Society';
        $this->template->setPropertyName($value);
        $this->assertEquals($value, $this->template->getAttribute('propertyName'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPropertyWebsiteInvalid()
    {
        $this->template->setPropertyWebsite('Invalid website url');
    }

    public function testSetPropertyWebsite()
    {
        $value = 'http://www.mywebsite.fr';
        $this->template->setPropertyWebsite($value);
        $this->assertEquals($value, $this->template->getAttribute('propertyWebsite'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetUrlMissingTimeZone()
    {
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setEndDate('2016-06-01 12:28:00');
        $this->template->setTitle('My event');
        $this->template->getUrl();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetUrlMissingStartDate()
    {
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setEndDate('2016-06-01 12:28:00');
        $this->template->setTitle('My event');
        $this->template->getUrl();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetUrlMissingEndDate()
    {
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setTitle('My event');
        $this->template->getUrl();
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetUrlMissingTitle()
    {
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setEndDate('2016-06-01 12:28:00');
        $this->template->getUrl();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetUrlInvalidDates()
    {
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setEndDate('2016-06-01 12:20:00');
        $this->template->setTitle('My event');
        $this->template->getUrl();
    }

    public function testGetUrlLight()
    {
        $expectedUrl = 'https://www.google.com/calendar/event?action=TEMPLATE&text=My+event&details=&dates=20160601T102500Z%2F20160601T102800Z&ctz=Europe%2FParis&location=&trp=false';
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setEndDate('2016-06-01 12:28:00');
        $this->template->setTitle('My event');
        $url = $this->template->getUrl();
        $this->assertEquals($expectedUrl, $url);
    }

    public function testGetUrlComplete()
    {
        $expectedUrl = 'https://www.google.com/calendar/event?action=TEMPLATE&text=My+event&details=&dates=20160601T102500Z%2F20160601T102800Z&ctz=Europe%2FParis&location=My+Private+Address&trp=true&sprop=name%3AMy+Society&sprop=website%3Ahttps%3A%2F%2Fwww.domain.fr&src=root%40localhost&add=root%40domain.fr';
        $this->template->setTimeZone('Europe/Paris');
        $this->template->setStartDate('2016-06-01 12:25:00');
        $this->template->setEndDate('2016-06-01 12:28:00');
        $this->template->setTitle('My event');
        $this->template->eventHasBusy();
        $this->template->setLocation('My Private Address');
        $this->template->setOwnerEmail('root@localhost');
        $this->template->addGuestEmail('root@domain.fr');
        $this->template->setPropertyName('My Society');
        $this->template->setPropertyWebsite('https://www.domain.fr');
        $url = $this->template->getUrl();
        $this->assertEquals($expectedUrl, $url);
    }

}