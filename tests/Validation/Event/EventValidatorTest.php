<?php

namespace CultuurNet\UDB3\Model\Validation\Event;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class EventValidatorTest extends TestCase
{
    /**
     * @var EventValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new EventValidator();
    }

    /**
     * @test
     */
    public function it_should_pass_all_required_properties_are_present_in_a_valid_format()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ]
            ],
        ];

        $this->assertTrue($this->validator->validate($event));
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_required_property_is_missing()
    {
        $event = [];

        $expectedErrors = [
            'Key @id must be present',
            'Key mainLanguage must be present',
            'Key name must be present',
            'Key terms must be present',
            'Key calendarType must be present',
            'Key location must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_id_is_in_an_invalid_format()
    {
        $event = [
            '@id' => 'http://io.uitdatabank.be/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Test event',
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        // @codingStandardsIgnoreStart
        $expectedErrors = [
            '@id must validate against "/\\\/event[s]?\\\/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})[\\\/]?/"',
        ];
        // @codingStandardsIgnoreEnd

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_mainLanguage_is_in_an_invalid_format()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'foo',
            'name' => [
                'nl' => 'Test event',
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'mainLanguage must validate against "/^[a-z]{2}$/"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_name_has_no_entries()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'name must have a length greater than 1',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_name_translation_is_empty()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Test event',
                'fr' => '',
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'name value must not be empty',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_name_translation_has_an_invalid_language()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Test event',
                'foo' => 'Test event',
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            '"foo" must validate against "/^[a-z]{2}$/"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_name_is_a_string()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => 'Example name',
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for name',
            'name must be of the type array',
            'Each item in name must be valid',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_has_an_unknown_value()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'foobar',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'At least one of these rules must pass for calendarType',
            'calendarType must be equal to "single"',
            'calendarType must be equal to "multiple"',
            'calendarType must be equal to "periodic"',
            'calendarType must be equal to "permanent"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_single_and_required_fields_are_missing()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'single',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for calendarType single',
            'Key startDate must be present',
            'Key endDate must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_single_and_startDate_or_endDate_is_malformed()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'single',
            'startDate' => '12/01/2018',
            'endDate' => '13/01/2018',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for calendarType single',
            'startDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"',
            'endDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_single_and_endDate_is_before_startDate()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'single',
            'startDate' => '2018-03-05T13:44:09+01:00',
            'endDate' => '2018-02-28T13:44:09+01:00',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'endDate must be greater than or equal to "2018-03-05T13:44:09+01:00"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_single_and_there_are_multiple_subEvents()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'single',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'subEvents' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-02-28T13:44:09+01:00',
                    'endDate' => '2018-03-01T13:44:09+01:00',
                ],
                [
                    '@type' => 'Event',
                    'startDate' => '2018-03-04T13:44:09+01:00',
                    'endDate' => '2018-03-05T13:44:09+01:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'calendarType single should have exactly one subEvent',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_multiple_and_required_fields_are_missing()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'multiple',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for calendarType multiple',
            'Key startDate must be present',
            'Key endDate must be present',
            'Key subEvents must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_multiple_and_a_subEvent_is_missing_required_fields()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'multiple',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-01T13:44:09+01:00',
            'subEvents' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-02-28T13:44:09+01:00',
                ],
                [
                    '@type' => 'Event',
                    'endDate' => '2018-03-01T13:44:09+01:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'Each item in subEvents must be valid',
            'Key endDate must be present',
            'Key startDate must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_multiple_and_there_is_only_one_subEvent()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'multiple',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-01T13:44:09+01:00',
            'subEvents' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-02-28T13:44:09+01:00',
                    'endDate' => '2018-03-01T13:44:09+01:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'subEvents must have at least 2 subEvent(s)',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_multiple_and_a_subEvent_has_a_malformed_date()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'multiple',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'subEvents' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-02-28T13:44:09+01:00',
                    'endDate' => '2018-03-01',
                ],
                [
                    '@type' => 'Event',
                    'startDate' => '2018-03-04',
                    'endDate' => '2018-03-05T13:44:09+01:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'Each item in subEvents must be valid',
            'endDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"',
            'startDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"'
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_multiple_and_endDate_is_after_startDate()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'multiple',
            'startDate' => '2018-03-05T13:44:09+01:00',
            'endDate' => '2018-02-28T13:44:09+01:00',
            'subEvents' => [
                [
                    '@type' => 'Event',
                    'startDate' => '2018-02-28T13:44:09+01:00',
                    'endDate' => '2018-03-01T13:44:09+01:00',
                ],
                [
                    '@type' => 'Event',
                    'startDate' => '2018-03-04T13:44:09+01:00',
                    'endDate' => '2018-03-05T13:44:09+01:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'endDate must be greater than or equal to "2018-03-05T13:44:09+01:00"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_required_fields_are_missing()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for calendarType periodic',
            'Key startDate must be present',
            'Key endDate must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_startDate_or_endDate_is_malformed()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'startDate' => '12/01/2018',
            'endDate' => '13/01/2018',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for calendarType periodic',
            'startDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"',
            'endDate must be a valid date. Sample format: "2005-12-30T01:02:03+00:00"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_an_openingHour_misses_required_fields()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08:00',
                ],
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'closes' => '16:00',
                ],
                [
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'Each item in openingHours must be valid',
            'Key closes must be present',
            'Key opens must be present',
            'Key dayOfWeek must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_opens_or_closes_is_malformed()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08h00',
                    'closes' => '16h00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for openingHour',
            'opens must be a valid date. Sample format: "01:02"',
            'closes must be a valid date. Sample format: "01:02"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_dayOfWeek_is_not_an_array()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'openingHours' => [
                [
                    'dayOfWeek' => 'monday',
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'All of the required rules must pass for dayOfWeek',
            'dayOfWeek must be of the type array',
            'Each item in dayOfWeek must be valid',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_periodic_and_dayOfWeek_has_an_unknown_value()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'periodic',
            'startDate' => '2018-02-28T13:44:09+01:00',
            'endDate' => '2018-03-05T13:44:09+01:00',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday', 'wed'],
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'At least one of these rules must pass for dayOfWeek',
            'dayOfWeek must be equal to "monday"',
            'dayOfWeek must be equal to "tuesday"',
            'dayOfWeek must be equal to "wednesday"',
            'dayOfWeek must be equal to "thursday"',
            'dayOfWeek must be equal to "friday"',
            'dayOfWeek must be equal to "saturday"',
            'dayOfWeek must be equal to "sunday"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_permanent_and_openingHour_misses_required_fields()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08:00',
                ],
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'closes' => '16:00',
                ],
                [
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'Each item in openingHours must be valid',
            'Key closes must be present',
            'Key opens must be present',
            'Key dayOfWeek must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_permanent_and_opens_or_closes_is_malformed()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '08h00',
                    'closes' => '16h00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'These rules must pass for openingHour',
            'opens must be a valid date. Sample format: "01:02"',
            'closes must be a valid date. Sample format: "01:02"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_permanent_and_opens_is_after_closes()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday'],
                    'opens' => '16:00',
                    'closes' => '08:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'closes must be greater than or equal to "16:00"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_permanent_and_dayOfWeek_is_not_an_array()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => 'monday',
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'All of the required rules must pass for dayOfWeek',
            'dayOfWeek must be of the type array',
            'Each item in dayOfWeek must be valid',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_calendarType_is_permanent_and_dayOfWeek_has_an_unknown_value()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'openingHours' => [
                [
                    'dayOfWeek' => ['monday', 'tuesday', 'wed'],
                    'opens' => '08:00',
                    'closes' => '16:00',
                ],
            ],
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'At least one of these rules must pass for dayOfWeek',
            'dayOfWeek must be equal to "monday"',
            'dayOfWeek must be equal to "tuesday"',
            'dayOfWeek must be equal to "wednesday"',
            'dayOfWeek must be equal to "thursday"',
            'dayOfWeek must be equal to "friday"',
            'dayOfWeek must be equal to "saturday"',
            'dayOfWeek must be equal to "sunday"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_both_location_id_and_address_are_missing()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                'foo' => 'bar',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        // @codingStandardsIgnoreStart
        $expectedErrors = [
            'At least one of these rules must pass for location',
            'Key location @id must be present',
            'Key location address must be present',
        ];
        // @codingStandardsIgnoreEnd

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_location_id_is_in_an_invalid_format()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => '9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        // @codingStandardsIgnoreStart
        $expectedErrors = [
            'All of the required rules must pass for location @id',
            'location @id must be an URL',
            'location @id must validate against "/\\\/place[s]?\\\/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})[\\\/]?/"',
        ];
        // @codingStandardsIgnoreEnd

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_location_address_has_no_entries()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                'address' => [],
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ],
            ],
        ];

        $expectedErrors = [
            'location address must have a length greater than 1',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_terms_is_empty()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [],
        ];

        $expectedErrors = [
            'terms must have at least 1 term(s)',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_term_is_missing_an_id()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'label' => 'foo',
                    'domain' => 'bar',
                ]
            ],
        ];

        $expectedErrors = [
            'Key term id must be present',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_term_has_an_id_that_is_not_a_string()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => 1,
                ]
            ],
        ];

        $expectedErrors = [
            'term id must be a string',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_audienceType_is_set_but_it_has_an_unknown_value()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ]
            ],
            'audience' => [
                'audienceType' => 'foo',
            ],
        ];

        $expectedErrors = [
            'At least one of these rules must pass for audience.audienceType',
            'audience.audienceType must be equal to "everyone"',
            'audience.audienceType must be equal to "members"',
            'audience.audienceType must be equal to "education"',
        ];

        $this->assertValidationErrors($event, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_pass_if_audienceType_is_set_to_a_known_value()
    {
        $event = [
            '@id' => 'https://io.uitdatabank.be/events/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Example name'
            ],
            'calendarType' => 'permanent',
            'location' => [
                '@id' => 'http://io.uitdatabank.be/place/9a344f43-1174-4149-ad9a-3e2e92565e35',
            ],
            'terms' => [
                [
                    'id' => '0.50.1.0.0',
                ]
            ],
            'audience' => [
                'audienceType' => 'everyone',
            ],
        ];

        $this->assertTrue($this->validator->validate($event));
    }

    /**
     * @param mixed $data
     * @param array $expectedMessages
     */
    private function assertValidationErrors($data, array $expectedMessages)
    {
        try {
            $this->getValidator()->assert($data);
            $this->fail('No error messages found.');
        } catch (NestedValidationException $e) {
            $actualMessages = $e->getMessages();

            if (count(array_diff($actualMessages, $expectedMessages)) > 0) {
                var_dump($actualMessages);
            }

            $this->assertEquals($expectedMessages, $actualMessages);
        }
    }

    /**
     * @return Validator
     */
    private function getValidator()
    {
        return $this->validator;
    }
}
