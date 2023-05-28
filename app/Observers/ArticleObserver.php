<?php

namespace App\Observers;

use App\Models\Article;
use Storage;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "saved" event.
     */
    public function saved(Article $article): void
    {
        if (
            $article->isDirty('banner') &&
            ! is_null($article->getOriginal('banner'))
        ) {
            Storage::disk('public')->delete(
                $article->getOriginal('banner')
            );
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        if (! is_null($article->banner)) {
            Storage::disk('public')->delete($article->banner);
        }
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
