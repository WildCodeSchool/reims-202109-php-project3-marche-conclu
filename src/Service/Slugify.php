<?php

namespace App\Service;

class Slugify
{
    private const CARACTERESTOREPLACE = [
        " ", "@", "#", "&", '"', "'", "(", "§", "!", ")", "°",
        "^", "¨", "$", "*", "€", "%", "`", "£", "=", "+", ":",
        "/", ".", ",", ";", "?", "<", ">", "|", "\""
    ];
    private const LETTERTOREPLACE = [
        "á" => "a", "à" => "a", "â" => "a", "ä" => "a", "ã" => "a",
        "å" => "a", "ç" => "c", "é" => "e", "è" => "e", "ê" => "e",
        "ë" => "e", "í" => "i", "ì" => "i", "î" => "i", "ï" => "i",
        "ñ" => "n", "ó" => "o", "ò" => "o", "ô" => "o", "ö" => "o",
        "õ" => "o", "ú" => "u", "ù" => "u", "û" => "u", "ü" => "u",
        "ý" => "y", "ÿ" => "y"];

    public function generate(string $firstname, string $lastname): string
    {
        $firstname = strtolower($firstname);
        $firstname = strtr($firstname, self::LETTERTOREPLACE);
        $firstname = str_replace(self::CARACTERESTOREPLACE, "", $firstname);
        $firstname = str_replace("--", "-", $firstname);

        $lastname = strtolower($lastname);
        $lastname = strtr($lastname, self::LETTERTOREPLACE);
        $lastname = str_replace(self::CARACTERESTOREPLACE, "", $lastname);
        $lastname = str_replace("--", "-", $lastname);

        $fullname = $lastname . $firstname;

        return $fullname;
    }
}
