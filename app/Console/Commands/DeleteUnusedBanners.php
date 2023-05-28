<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnusedBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unused-banners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused banners from storage/app/public/banners folder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $articles = Article::pluck('banner')->toArray();

        collect(Storage::disk('public')->allFiles())
            ->reject(fn (string $file) => $file === '.gitignore')
            ->reject(fn (string $file) => in_array($file, $articles))
            ->each(function (string $file) {
                Storage::disk('public')->delete($file);
                $this->info("Deleted: $file");
            });
    }
}
