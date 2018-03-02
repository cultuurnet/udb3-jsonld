<?php

namespace CultuurNet\UDB3\Model\Validation\Organizer;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class OrganizerValidatorTest extends TestCase
{
    /**
     * @var OrganizerValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new OrganizerValidator();
    }

    /**
     * @test
     */
    public function it_should_pass_if_all_required_properties_are_present_in_a_valid_format()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $this->assertTrue($this->validator->validate($organizer));
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_required_property_is_missing()
    {
        $organizer = [];

        $expectedErrors = [
            'Key @id must be present',
            'Key mainLanguage must be present',
            'Key name must be present',
            'Key url must be present',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_id_is_in_an_invalid_format()
    {
        $organizer = [
            '@id' => 'http://io.uitdatabank.be/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
        ];

        // @codingStandardsIgnoreStart
        $expectedErrors = [
            '@id must validate against "/\\\/organizer[s]?\\\/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})[\\\/]?/"',
        ];
        // @codingStandardsIgnoreEnd

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_mainLanguage_is_in_an_invalid_format()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'foo',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $expectedErrors = [
            'mainLanguage must validate against "/^[a-z]{2}$/"',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_name_has_no_entries()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [],
            'url' => 'https://www.publiq.be',
        ];

        $expectedErrors = [
            'name must have a length greater than 1',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_name_translation_is_empty()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => '',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $expectedErrors = [
            'name value must not be empty',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_a_name_translation_has_an_invalid_language()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'foo' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
        ];

        $expectedErrors = [
            '"foo" must validate against "/^[a-z]{2}$/"',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_name_is_a_string()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => 'Publiq vzw',
            'url' => 'https://www.publiq.be',
        ];

        $expectedErrors = [
            'name must be of the type array',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_url_is_in_an_invalid_format()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'info@publiq.be',
        ];

        $expectedErrors = [
            'url must be an URL',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_labels_is_set_but_not_an_array()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'labels' => 'foo,bar',
        ];

        $expectedErrors = [
            'labels must be of the type array',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_labels_is_set_but_contains_something_different_than_a_string()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'labels' => [
                ['name' => 'foo', 'visible' => true],
            ],
        ];

        $expectedErrors = [
            'each label must be a string',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_pass_if_labels_is_an_array_of_strings()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'labels' => [
                'foo',
                'bar',
            ],
        ];

        $this->assertTrue($this->validator->validate($organizer));
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_hiddenLabels_is_set_but_not_an_array()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'hiddenLabels' => 'foo,bar',
        ];

        $expectedErrors = [
            'hiddenLabels must be of the type array',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_throw_an_exception_if_hiddenLabels_is_set_but_contains_something_different_than_a_string()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'hiddenLabels' => [
                ['name' => 'foo', 'visible' => true],
            ],
        ];

        $expectedErrors = [
            'each label must be a string',
        ];

        $this->assertValidationErrors($organizer, $expectedErrors);
    }

    /**
     * @test
     */
    public function it_should_pass_if_hiddenLabels_is_an_array_of_strings()
    {
        $organizer = [
            '@id' => 'https://io.uitdatabank.be/organizers/b19d4090-db47-4520-ac1a-880684357ec9',
            'mainLanguage' => 'nl',
            'name' => [
                'nl' => 'Publiq vzw',
            ],
            'url' => 'https://www.publiq.be',
            'hiddenLabels' => [
                'foo',
                'bar',
            ],
        ];

        $this->assertTrue($this->validator->validate($organizer));
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
