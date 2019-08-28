<?php

namespace Jebog\Blogged\Tests\Unit;

use Jebog\Blogged\Tests\TestCase;
use Jebog\Blogged\Models\Article;
use Jebog\Blogged\Models\Category;

class CategoryTest extends TestCase
{
    /** @test */
    public function it_has_title()
    {
        $category = factory(Category::class)->create(['title' => 'education']);

        $this->assertEquals('education', $category->title);
    }

    /** @test */
    public function it_has_unique_slug()
    {
        $category = factory(Category::class)->create(['slug' => 'education']);
        $this->assertEquals('education', $category->slug);
        
        $this->expectException('\Illuminate\Database\QueryException');
        $category = factory(Category::class)->create(['slug' => 'education']);
    }

    /** @test */
    public function it_has_many_articles()
    {
        $category = factory(Category::class)->create();
        
        factory(Article::class)->create(['category_id' => $category->id]);

        $this->assertCount(1, $category->articles);
    }

    /** @test */
    public function it_has_uri_path()
    {
        $category = factory(Category::class)->create(['slug' => 'education']);

        $this->assertEquals(route('blogged.index', $category->slug), $category->path());
    }
}
