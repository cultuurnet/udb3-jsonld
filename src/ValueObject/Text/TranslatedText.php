<?php

namespace CultuurNet\UDB3\Model\ValueObject\Text;

abstract class TranslatedText
{
    /**
     * @var Language
     */
    private $originalLanguage;

    /**
     * @var array
     */
    private $translations;

    /**
     * @param Language $originalLanguage
     * @param mixed $originalText
     */
    public function __construct(Language $originalLanguage, $originalText)
    {
        $this->guardTextClassName($originalText);

        $this->originalLanguage = $originalLanguage;
        $this->translations[$originalLanguage->getCode()] = $originalText;
    }

    /**
     * @todo Use generics instead, if/when ever available in PHP.
     * @return string
     */
    abstract protected function getTextClassName();

    /**
     * @param Language $language
     * @param mixed $translation
     * @return static
     */
    public function withTranslation(Language $language, $translation)
    {
        $this->guardTextClassName($translation);

        $c = clone $this;
        $c->translations[$language->getCode()] = $translation;
        return $c;
    }

    /**
     * @param Language $language
     * @return static
     */
    public function withoutTranslation(Language $language)
    {
        if ($language->sameAs($this->originalLanguage)) {
            throw new \InvalidArgumentException('Can not remove translation of the original language.');
        }

        $c = clone $this;
        unset($c->translations[$language->getCode()]);
        return $c;
    }

    /**
     * @param Language $language
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function getTranslation(Language $language)
    {
        $languageCode = $language->getCode();

        if (!isset($this->translations[$languageCode])) {
            throw new \OutOfBoundsException("No translation found for language {$languageCode}");
        }

        return $this->translations[$languageCode];
    }

    /**
     * @return Language
     */
    public function getOriginalLanguage()
    {
        return $this->originalLanguage;
    }

    /**
     * @return Languages
     */
    public function getLanguages()
    {
        $languageKeys = array_keys($this->translations);

        $languageObjects = array_map(
            function ($languageCode) {
                return new Language($languageCode);
            },
            $languageKeys
        );

        return new Languages(...$languageObjects);
    }

    /**
     * @return Languages
     */
    public function getLanguagesWithoutOriginal()
    {
        return $this->getLanguages()->filter(
            function (Language $language) {
                return !$language->sameAs($this->originalLanguage);
            }
        );
    }

    /**
     * @param mixed $text
     */
    private function guardTextClassName($text)
    {
        $className = $this->getTextClassName();
        if (!($text instanceof $className)) {
            $actualClassName = is_scalar($text) ? gettype($text) : get_class($text);
            throw new \InvalidArgumentException("The given object is a {$actualClassName}, expected {$className}.");
        }
    }
}
