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

    private function slug(?string $firstname, ?string $lastname): string
    {
        $fullname = $lastname . $firstname;

        $fullname = strtolower($fullname);
        $fullname = strtr($fullname, self::LETTERTOREPLACE);
        $fullname = str_replace(self::CARACTERESTOREPLACE, "", $fullname);
        $fullname = str_replace("--", "-", $fullname);

        return $fullname;
    }

    public function assignSlug(?string $firstname, ?string $lastname): string
    {
        return $this->slug($lastname, $firstname);
    }
}
