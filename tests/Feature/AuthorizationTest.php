<?php

namespace Jebog\Blogged\Tests\Feature;

use Illuminate\Support\Facades\Gate;
use Jebog\Blogged\Tests\TestCase;
use Jebog\Blogged\Models\Article;
use Jebog\Blogged\Tests\Fixture\ArticlePolicy;

class AuthorizationTest extends TestCase
{
    /** @test */
    public function a_model_with_policy_all_its_actions_will_follow_it()
    {
        $this->assertFalse(Article::hasAuthorizationGate());

        Gate::policy(Article::class, ArticlePolicy::class);

        $this->assertTrue(Article::hasAuthorizationGate());
    }
}
