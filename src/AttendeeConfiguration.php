<?php

namespace Appointment;

use function Appointment\filterFilePath;

/**
 * Class to provide initial configuration
 * for appointment app
 * Values provided from file constants
 */
class AttendeeConfiguration
{
    /**
     * File to provide app settings
     */
    const CONFIGURATION_FILE = 'config.json';
    /**
     * File to provide credentials for google api
     * calendar
     */
    const CREDENTIAL_FILE = 'credential.json';
    /**
     * Application name
     * @var string
     */
    private $applicationName;
    /**
     * Calendar used in google calendar
     * @var string
     */
    private $calendarId;
    /**
     * Access type for google api calendar
     * @var string
     */
    private $accessType;
    /**
     * Client secret for google api calendar
     * @var array
     */
    private $clientSecret;
    /**
     * Oauth for google api calendar
     * @var array
     */
    private $oauth;
    /**
     * Usable activity slots
     * e.g : monday :{10:00 - 17:00}
     * @var array
     */
    private $dateSlots;
    /**
     * Event types
     * e.g : interview,meeting
     * @var array
     */
    private $eventTypes;

    /**
     * Constructor
     */
    public function __construct($args = null)
    {
        if (is_null($args)) {
            $this->setupAppSettings();

            $this->setupCredentials();
        }
    }
    /**
     * Get application name
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }
    /**
     * Get calendar Id
     * @return string
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }
    /**
     * Get access type
     * @return string
     */
    public function getAccessType()
    {
        return $this->accessType;
    }
    /**
     * Get client Secret
     * @return array
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
    /**
     * Get oauth
     * @return array
     */
    public function getOauth()
    {
        return $this->oauth;
    }
    /**
     * Get date slots on config file
     * @return array
     */
    public function getDateSlots()
    {
        return $this->dateSlots;
    }
    /**
     * Get event types
     * @return array
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }
    /**
     * Setup application settings
     * Based on configuration
     * @return array
     */
    private function setupAppSettings()
    {
        $appSettings = json_decode(
            file_get_contents(
                filterFilePath(getcwd() . '/' . self::CONFIGURATION_FILE)
            ),
            true
        );

        $this->applicationName = $appSettings['application_name'];

        $this->calendarId = $appSettings['calendar_id'];

        $this->accessType = $appSettings['access_type'];

        $this->dateSlots = $appSettings['available_slots'];

        $this->eventTypes = $appSettings['event_types'];
    }
    /**
     * Setup credential
     * @return void
     */
    private function setupCredentials()
    {
        $credentials = json_decode(
            file_get_contents(
                filterFilePath(getcwd() . '/' . self::CREDENTIAL_FILE)
            ),
            true
        );

        $this->clientSecret = $credentials['client_secret'];

        $this->oauth = $credentials['oauth'];
    }
}
