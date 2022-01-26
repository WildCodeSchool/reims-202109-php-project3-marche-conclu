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
            ['Lorem', 'Ipsum', 'lorem-ipsum'],
            ['  Lorem', 'Ipsum  ', 'lorem-ipsum'],
            [' lOrEm ', 'iPsUm  ', 'lorem-ipsum'],
            ['!Lorem', 'Ipsum!', '!lorem-ipsum!'],
            ['lorem-ipsum', '', 'lorem-ipsum'],
            ['lorem 日本語', 'ipsum', 'lorem-日本語-ipsum'],
            ['lorem русский язык', 'ipsum', 'lorem-русский-язык-ipsum'],
        ];
    }
}
