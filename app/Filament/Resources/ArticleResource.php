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
                // TODO: Improve image resizing and helper text.
                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->helperText(
                        'The image must be a valid file format, with a minimum width of 900px, and a maximum file size of 1MB.'
                    )
                    ->image()
                    ->maxSize(1024)
                    ->imageResizeMode('contain')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('900')
                    ->imageResizeTargetHeight('506')
                    ->imageResizeUpscale(false)
                    ->disk('public')
                    ->directory('articles/thumbnails')
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
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->inline(false),
                    Forms\Components\DatePicker::make('published_at')->label(
                        'Publish Date'
                    ),
                ]),
            ]),
            Forms\Components\Card::make()->schema([
                Forms\Components\Builder::make('content')
                    ->blocks([
                        Forms\Components\Builder\Block::make('heading')
                            ->schema([
                                Forms\Components\TextInput::make('content')
                                    ->label('Heading')
                                    ->required(),
                                Forms\Components\Select::make('level')
                                    ->options([
                                        'h2' => 'Heading 2',
                                        'h3' => 'Heading 3',
                                        'h4' => 'Heading 4',
                                        'h5' => 'Heading 5',
                                        'h6' => 'Heading 6',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Builder\Block::make('paragraph')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->label('Paragraph')
                                    ->extraInputAttributes([
                                        'style' => 'min-height: 16rem;',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Builder\Block::make('image')
                            ->schema([
                                // TODO: Improve image resizing.
                                Forms\Components\FileUpload::make('content')
                                    ->label('Image')
                                    ->image()
                                    ->maxSize(1024)
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('900')
                                    ->imageResizeTargetHeight('506')
                                    ->imageResizeUpscale(false)
                                    ->disk('public')
                                    ->directory('articles/images')
                                    ->visibility('public')
                                    ->required(),
                                Forms\Components\TextInput::make('alt')
                                    ->label('Alt Text')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),
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
                Tables\Columns\TextColumn::make('category.name')
                    ->placeholder('Uncategorized'),
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
