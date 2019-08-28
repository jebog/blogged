<?php

namespace Jebog\Blogged\Traits;

use Jebog\Blogged\Models\Article;

trait CanWriteArticles
{
    /**
     * @return hasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }
}
