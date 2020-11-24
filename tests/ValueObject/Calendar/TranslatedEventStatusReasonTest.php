<?php

namespace CultuurNet\UDB3\Model\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use PHPUnit\Framework\TestCase;

class TranslatedEventStatusReasonTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_only_accept_an_event_status_reason_as_original_text_value(): void
    {
        $className = EventStatusReason::class;
        $invalidClassName = Title::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        new TranslatedEventStatusReason(new Language('nl'), new Title('foo'));
    }

    /**
     * @test
     */
    public function it_should_only_accept_an_event_status_reason_as_translation()
    {
        $nl = new Language('nl');
        $nlValue = new EventStatusReason('foo');
        $translatedDescription = (new TranslatedEventStatusReason($nl, $nlValue));

        $className = EventStatusReason::class;
        $invalidClassName = Title::class;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The given object is a {$invalidClassName}, expected {$className}.");

        $translatedDescription->withTranslation(new Language('fr'), new Title('foo'));
    }
}
