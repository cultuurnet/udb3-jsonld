<?php

namespace CultuurNet\UDB3\Model\ValueObject\Contact;

use PHPUnit\Framework\TestCase;

class ContactPointTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_creatable_without_any_parameters()
    {
        $contactPoint = new ContactPoint();

        $this->assertEquals(new TelephoneNumbers(), $contactPoint->getTelephoneNumbers());
        $this->assertEquals(new EmailAddresses(), $contactPoint->getEmailAddresses());
        $this->assertEquals(new Urls(), $contactPoint->getUrls());
        $this->assertTrue($contactPoint->isEmpty());
    }
}
