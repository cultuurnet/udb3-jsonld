<?php

namespace CultuurNet\UDB3\Model\Serializer\ValueObject\Price;

use CultuurNet\UDB3\Model\ValueObject\Price\PriceInfo;
use CultuurNet\UDB3\Model\ValueObject\Price\Tariff;
use CultuurNet\UDB3\Model\ValueObject\Price\TariffName;
use CultuurNet\UDB3\Model\ValueObject\Price\Tariffs;
use CultuurNet\UDB3\Model\ValueObject\Price\TranslatedTariffName;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use Money\Currency;
use Money\Money;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PriceInfoDenormalizer implements DenormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("PriceInfoDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('PriceInfo data should be an array.');
        }

        $basePriceData = [];
        $tariffsData = [];

        foreach ($data as $tariffData) {
            if ($tariffData['category'] === 'base') {
                $basePriceData = $tariffData;
                continue;
            }

            $tariffsData[] = $tariffData;
        }

        $basePrice = $this->denormalizeTariff($basePriceData, $context);

        $tariffs = array_map(
            function ($tariffData) use ($context) {
                return $this->denormalizeTariff($tariffData, $context);
            },
            $tariffsData
        );

        return new PriceInfo($basePrice, new Tariffs(...$tariffs));
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === PriceInfo::class;
    }

    /**
     * @todo Extract to a separate TariffDenormalizer
     * @param array $tariffData
     * @param array $context
     * @return Tariff
     */
    private function denormalizeTariff(array $tariffData, array $context = [])
    {
        $languageKeys = array_keys($tariffData['name']);

        if (isset($context['originalLanguage'])) {
            $mainLanguageKey = $context['originalLanguage'];
        } else {
            $mainLanguageKey = $languageKeys[0];
        }

        $mainLanguage = new Language($mainLanguageKey);

        $tariffName = new TranslatedTariffName($mainLanguage, new TariffName($tariffData['name'][$mainLanguageKey]));
        foreach ($tariffData['name'] as $languageKey => $name) {
            if ($languageKey === $mainLanguageKey) {
                continue;
            }

            $tariffName = $tariffName->withTranslation(
                new Language($languageKey),
                new TariffName($name)
            );
        }

        return new Tariff(
            $tariffName,
            new Money((int) ($tariffData['price']*100), new Currency('EUR'))
        );
    }
}
