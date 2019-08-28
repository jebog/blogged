<?php

namespace Jebog\Blogged\Tests\Unit;

use Jebog\Blogged\Models\Article;
use Jebog\Blogged\Tests\TestCase;
use Jebog\Blogged\Models\Category;
use Jebog\Blogged\Tests\Fixture\User;

class ArticleTest extends TestCase
{
    /** @test */
    public function it_has_title()
    {
        $article = factory(Article::class)->create(['title' => 'who are you?']);

        $this->assertEquals('who are you?', $article->title);
    }

    /** @test */
    public function it_has_unique_slug()
    {
        $article = factory(Article::class)->create(['slug' => 'slug-me']);
        $this->assertEquals('slug-me', $article->slug);
        
        $this->expectException('\Illuminate\Database\QueryException');
        $article = factory(Article::class)->create(['slug' => 'slug-me']);
    }

    /** @test */
    public function it_has_image()
    {
        $article = factory(Article::class)->create(['image' => 'image-me.com']);

        $this->assertEquals('/storage/image-me.com', $article->image);
    }

    /** @test */
    public function it_has_body()
    {
        $article = factory(Article::class)->create(['body' => 'bla bla bla']);

        $this->assertEquals('bla bla bla', $article->body);
    }

    /** @test */
    public function it_has_excerpt()
    {
        $article = factory(Article::class)->create(['excerpt' => 'bla bla blo']);

        $this->assertEquals('bla bla blo', $article->excerpt);
    }

    /** @test */
    public function it_has_publish_date()
    {
        $article = factory(Article::class)->create();

        $this->assertNotNull($article->publish_date);
    }

    /** @test */
    public function it_is_not_published_by_default()
    {
        $article = factory(Article::class)->create();

        $this->assertFalse($article->published);
    }

    /** @test */
    public function it_is_not_featured_by_default()
    {
        $article = factory(Article::class)->create();

        $this->assertFalse($article->featured);
    }

    /** @test */
    public function it_can_be_published()
    {
        $article = factory(Article::class)->create();

        $article->publish();

        $this->assertTrue($article->published);
    }

    /** @test */
    public function it_can_be_featured()
    {
        $article = factory(Article::class)->create();

        $article->feature();

        $this->assertTrue($article->featured);
    }

    /** @test */
    public function it_can_get_scheduled()
    {
        $this->assertCount(0, Article::scheduled()->get());

        factory(Article::class)->create([
            'publish_date' => now()->subMinute(),
            'published'    => false,
        ]);
        $this->assertCount(1, Article::scheduled()->get());

        factory(Article::class)->create([
            'publish_date' => now()->subMinute(),
            'published'    => true,
        ]);
        $this->assertCount(1, Article::scheduled()->get());

        factory(Article::class)->create([
            'publish_date' => now()->addMinute(),
            'published'    => false,
        ]);
        $this->assertCount(2, Article::scheduled()->get());

        factory(Article::class)->create([
            'publish_date' => null,
            'published'    => false,
        ]);
        $this->assertCount(2, Article::scheduled()->get());
    }

    /** @test */
    public function it_has_uri_path()
    {
        $article = factory(Article::class)->create(['slug' => 'how-to-cook-laravel-application']);

        $this->assertEquals(route('blogged.show', [$article->category->slug, $article->slug]), $article->path());
    }

    /** @test */
    public function it_parse_its_body_with_parsedBody_attribute()
    {
        $article = factory(Article::class)->create(['body' => '#hello']);
        $this->assertEquals('<h1>hello</h1>', $article->parsedBody);

        $article = factory(Article::class)->create(['body' => '<script>alert(1);</script>']);
        $this->assertEquals('', $article->parsedBody);
    }

    /** @test */
    public function it_has_author()
    {
        $article = factory(Article::class)->create();

        $this->assertInstanceOf(User::class, $article->author);
    }

    /** @test */
    public function it_belongs_to_category()
    {
        $article = factory(Article::class)->create();

        $this->assertInstanceOf(Category::class, $article->category);
    }
}
