<?php

namespace Appointment;

use Appointment\AttandeeConfiguration;

/**
 * Google Api Client
 */
class Attendee
{
    private $googleClient;

    private $googleService;

    private $config;
    
    /**
     * Consturctor
     * @param AttandeeConfiguration $config
     */
    public function __construct(AttandeeConfiguration $config)
    {
        $this->config = $config;

        $this->initState();
    }
    /**
     * Init Client and Service State
     * @return void
     */
    private function initState()
    {
        $this->googleClient = new \Google_Client();
        $this->googleClient->setApplicationName(
            $this->config->getApplicationName()
        );
        $this->googleClient->setScopes(
            \Google_Service_Calendar::CALENDAR
        );
        $this->googleClient->setAuthConfig(
            $this->config->getClientSecret()
        );
        $this->googleClient->setAccessType(
            $this->config->getAccessType()
        );
        $this->googleClient->setAccessToken(
            $this->config->getOauth()
        );
        $this->refreshToken();
        $this->googleService = new \Google_Service_Calendar(
            $this->googleClient
        );
    }
    /**
     * List events based on calendarID
     * @param  string $startTime string date('c') format
     * @param  string $endTime string date('c') format
     * @return bool|array
     */
    public function listEvents($startTime, $endTime)
    {
        $calendarId = $this->config->getCalendarId();

        $optParams = array(
            'maxResults'   => 10,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => $startTime,
            'timeMax'      => $endTime
        );

        try {
            $results = $this->googleService->events->listEvents($calendarId, $optParams);

            $events = array();

            foreach ($results->getItems() as $event) {
                array_push($events, $event->getSummary());
            }

            return $events;
        } catch (\Google_Service_Exception $e) {
            return false;
        }
    }
    /**
     * Get configuration
     * @return [type] [description]
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * Get google client
     * @return \Google_Client
     */
    public function getClient()
    {
        $client = clone $this->googleClient;

        return $client;
    }
    /**
     * Refresh token each time new object created
     * @return void
     */
    private function refreshToken()
    {
        if ($this->googleClient->isAccessTokenExpired()) {
            $this->googleClient->fetchAccessTokenWithRefreshToken(
                $this->googleClient->getRefreshToken()
            );
        }
    }
}
