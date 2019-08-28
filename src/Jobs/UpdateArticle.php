<?php

namespace Jebog\Blogged\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Queue\SerializesModels;
use Jebog\Blogged\Models\Article;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Jebog\Blogged\Http\Requests\UpdateArticleFormRequest;

class UpdateArticle
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $article;
    protected $request;

    /**
     * __construct
     *
     * @param Article $article
     * @param UpdateArticleFormRequest $request
     * @return void
     */
    public function __construct(Article $article, UpdateArticleFormRequest $request)
    {
        $this->article = $article;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->request->has('body')) {
            $body = Purifier::clean($this->request->body, [
                'AutoFormat.AutoParagraph' => false,
                'AutoFormat.RemoveEmpty'   => false,
            ]);
            $this->request->body = $body;
        }

        if($this->request->has('image') && ($this->article->image != $this->request->image)) {
            $this->article->updateImage($this->request->image);
        }

        $allowdAttributes = ['title', 'slug', 'excerpt', 'body', 'publish_date', 'published', 'featured', 'category_id'];
        $this->article->fill($this->request->only($allowdAttributes));

        $this->article->save();

        if($this->request->published) {
            $this->article->publish();
        }
    }
}
