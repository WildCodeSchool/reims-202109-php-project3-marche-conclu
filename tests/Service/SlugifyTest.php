<?php

namespace App\Tests\Service;

use App\Service\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
   /**
    * @dataProvider getSlugs
    */
    public function testAssignSlug(string $firstString, string $lastString, string $slug): void
    {
        $slugify = new Slugify();
        $this->assertSame($slug, $slugify->assignSlug($firstString, $lastString));
    }

    public function getSlugs(): array
    {
        return [
            ['Lorem', 'Ipsum', 'loremipsum'],
            ['  Lorem', 'Ipsum  ', 'loremipsum'],
            [' lOrEm ', 'iPsUm  ', 'loremipsum'],
            ['!Lorem', 'Ipsum!', 'loremipsum'],
            ['lorem-ipsum', '', 'lorem-ipsum'],
            ['lorem 日本語', 'ipsum', 'lorem日本語ipsum'],
            ['lorem русский язык', 'ipsum', 'loremрусскийязыкipsum'],
        ];
    }
}
