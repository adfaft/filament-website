<?php
namespace App\Filament\Components\Forms;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload as FilamentSpatieMediaLibraryUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * hanya bug fixing untuk key berupa json
 * NOTE: hanya berlaku untuk key milik sendiri, tidak bisa via relationship
 */
class SpatieMediaLibraryFileUpload extends FilamentSpatieMediaLibraryUpload{
    
    protected function setUp(): void
    {
        parent::setUp();


        $this->dehydrated(true);

        $this->collection(static function(SpatieMediaLibraryFileUpload $component){
            return $component->getName();
        });

        // $this->mutateDehydratedStateUsing(function(SpatieMediaLibraryFileUpload $component, Model&HasMedia $record, $state){
        //     $media = array_values($component->getSpatieState($component, $record));

        //     if ($component->isMultiple()) {
        //         return $media;
        //     }

        //     return $media[0] ?? null;
        // });

        // $this->saveRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, Model&HasMedia $record, $state): void {

        //     Log::info("key : ", [ $component->getName() ] );

        //     // hanya bug fixing untuk key berupa json
        //     if( ! $component->keyIsJsonInModel() ){
        //         return;
        //     }

        //     $media = $component->getSpatieState($component, $record);

        //     $media_all = $record->load("media")->media()->get()->mapWithKeys(fn($item) => [ $item->getAttributeValue('uuid') => $item->getAttributeValue('uuid')])->toArray();
        //     Log::info("media : ", $media);
        //     Log::info("media all : ", $media_all);
        //     Log::info("state", [$state]);

        //     $record->update([
        //         str_replace(".", "->", $component->getName()) => $media
        //     ]);
        // });
    }

    public function getSpatieState(SpatieMediaLibraryFileUpload $component, Model&HasMedia $record): array
    {
        /** @var Model&HasMedia $record */
        $media = $record->load('media')->getMedia($component->getCollection() ?? 'default')
        ->when(
            $component->hasMediaFilter(),
            fn (Collection $media) => $component->filterMedia($media)
        )
        ->when(
            ! $component->isMultiple(),
            fn (Collection $media): Collection => $media->take(1),
        )
        ->mapWithKeys(function (Media $media): array {
            $uuid = $media->getAttributeValue('uuid');

            return [$uuid => $uuid];
        })
        ->toArray();

        return $media;
    }

    public function keyIsJsonInModel(): bool
    {
        if( strpos($this->name, ".") === false){
            return false;
        }

        $key = explode(".", $this->name)[0];
        if( in_array($this->getRecord()->getCasts()[$key], ['array', 'json']) ){
            return true;
        }

        return false;
    }
}