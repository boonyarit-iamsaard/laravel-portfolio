<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unused-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused images from storage/app/public/thumbnails and storage/app/public/images';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $articles = Article::all();

        $usedThumbnails = $articles->pluck('thumbnail')->toArray();
        $usedImages = [];

        foreach ($articles as $article) {
            $content = $article->content;
            $imageUrls = $this->getImageUrls($content);
            $usedImages = array_merge($usedImages, $imageUrls);
        }

        // Note to self: By using array_filter() without a defined callback,
        // we remove all null, false, 0, '', [], and '0' values from the array.
        $usedThumbnails = array_filter($usedThumbnails);
        $usedImages = array_filter($usedImages);

        $usedFiles = array_merge($usedThumbnails, $usedImages);

        collect(Storage::disk('public')->allFiles())
            ->reject(fn (string $file) => $file === '.gitignore')
            ->reject(fn (string $file) => in_array($file, $usedFiles))
            ->each(function (string $file) {
                Storage::disk('public')->delete($file);
                $this->info("Deleted: $file");
            });
    }

    // TODO: Make it reusable
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
