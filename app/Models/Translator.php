<?php
use Stichoza\GoogleTranslate\GoogleTranslate;

class Translator
{
    public static function translateText($text)
{
    $translator = new GoogleTranslate();
    $translator->setSource('en'); 
    $translator->setTarget(App::getLocale()); 
    $text = $translator->translate($text);
    return $text;
}
}

