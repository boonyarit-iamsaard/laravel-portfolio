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
        $isThumbnailDirtyAndHasOriginal = $article->isDirty('thumbnail') &&
            ! is_null($article->getOriginal('thumbnail'));

        if ($isThumbnailDirtyAndHasOriginal) {
            Storage::disk('public')->delete(
                $article->getOriginal('thumbnail')
            );
        }

        $isContentDirtyAndHasOriginal = $article->isDirty('content') &&
            ! is_null($article->getOriginal('content'));

        if ($isContentDirtyAndHasOriginal) {
            $originalContent = $article->getOriginal('content');
            $newContent = $article->content;

            $originalImageUrls = $this->getImageUrls($originalContent);
            $newImageUrls = $this->getImageUrls($newContent);

            $imageUrlsToDelete = array_diff($originalImageUrls, $newImageUrls);

            foreach ($imageUrlsToDelete as $imageUrl) {
                Storage::disk('public')->delete($imageUrl);
            }
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
        $hasThumbnail = ! is_null($article->thumbnail);

        if ($hasThumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $content = $article->content;
        $imageUrls = $this->getImageUrls($content);

        foreach ($imageUrls as $imageUrl) {
            Storage::disk('public')->delete($imageUrl);
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

    // TODO: Make it reusable.
    /**
     * Extract the image URLs from the content array.
     */
    private function getImageUrls(array $content): array
    {
        $imageUrls = [];

        foreach ($content as $item) {
            if ($item['type'] === 'image' && isset($item['data']['content'])) {
                $imageUrls[] = $item['data']['content'];
            }
        }

        return $imageUrls;
    }
}
