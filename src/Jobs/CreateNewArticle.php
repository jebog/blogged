<?php

namespace Jebog\Blogged\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Jebog\Blogged\Models\Article;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateNewArticle
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $article = Article::create([
            'title'        => request()->title, 
            'slug'         => request()->slug,
            'image'        => request()->image, 
            'excerpt'      => request()->excerpt, 
            'body'         => request()->body, 
            'publish_date' => request()->publish_date,
            'featured'     => request()->featured,
            'category_id'  => request()->category_id,
            'author_id'    => auth()->id(),
        ]);

        if(request()->published) {
            $article->publish();
        }
    }
}
