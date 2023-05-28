<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\FileUpload::make('banner')
                    ->label('Banner')
                    ->helperText(
                        'The image must be a valid file format, with a minimum width of 900px, a minimum height of 256px, and a maximum file size of 1MB.'
                    )
                    ->image()
                    ->maxSize(1024)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth(900)
                    ->imagePreviewHeight('256')
                    ->imageResizeUpscale(false)
                    ->disk('public')
                    ->directory('articles/banners')
                    ->visibility('public'),
            ]),
            Forms\Components\Card::make()->schema([
                Forms\Components\Grid::make([
                    'DEFAULT' => 1,
                    'sm' => 2,
                ])->schema([
                    Forms\Components\TextInput::make('title')
                        ->columnSpanFull()
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\Select::make('category_id')
                        ->columnSpanFull()
                        ->relationship('category', 'name'),
                    Forms\Components\RichEditor::make('body')
                        ->columnSpanFull()
                        ->extraInputAttributes([
                            'style' => 'min-height: 16rem;',
                        ]),
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->inline(false),
                    Forms\Components\DatePicker::make('published_at')->label(
                        'Publish Date'
                    ),
                ]),
            ]),
        ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('author.name'),
                Tables\Columns\TextColumn::make('category.name')->placeholder(
                    'Uncategorized'
                ),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->falseIcon('heroicon-o-ban')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->since(),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
