<?php

namespace Jebog\Blogged\Http\Controllers;

use Illuminate\Http\Request;
use Jebog\Blogged\Models\Article;
use Jebog\Blogged\Jobs\UpdateArticle;
use Jebog\Blogged\Jobs\CreateNewArticle;
use Jebog\Blogged\Http\Resources\ArticleResource;
use Jebog\Blogged\Http\Resources\ArticleMinimalResource;
use Jebog\Blogged\Http\Requests\CreateArticleFormRequest;
use Jebog\Blogged\Http\Requests\UpdateArticleFormRequest;

class ArticleController extends Controller
{
    /**
     * Get all available articles.
     */
    public function index()
    {
        $pagination = config('blogged.settings.pagination');

        $articles = Article::orderBy('created_at', 'DESC')
            ->orderBy('publish_date', 'DESC')
            ->paginate($pagination);

        return ArticleMinimalResource::collection($articles)
            ->additional(['statistics' => [
                'total'     => $articles->total(),
                'published' => Article::published()->count(),
                'featured'  => Article::featured()->count(),
            ]]);
    }

    /**
     * Show a given article.
     */
    public function show(Article $article, Request $request)
    {
        $article->authorizeToView($request);

        $article->load(['category', 'author']);

        return new ArticleResource($article);
    }

    /**
     * Create new blog article.
     */
    public function store(CreateArticleFormRequest $request)
    {
        Article::authorizeToCreate($request);

        CreateNewArticle::dispatch();
        
        return response()->json([], 201);
    }

    /**
     * @return response
     */
    public function update(Article $article, UpdateArticleFormRequest $request)
    {
        $article->authorizeToUpdate($request);
        
        UpdateArticle::dispatch($article, $request);

        return response()->json([], 204);
    }

    /**
     * @return response
     */
    public function destroy(Article $article, Request $request)
    {
        $article->authorizeToDelete($request);

        $article->delete();

        return response()->json([], 204);
    }
}
