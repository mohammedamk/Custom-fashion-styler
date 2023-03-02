<?php

namespace App\Console\Commands;

use App\Jobs\MoveMediaToDiskJob;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MoveMediaToDiskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move-media-to-disk {fromDisk} {toDisk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move media from disk to a new disk';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $diskNameFrom = $this->argument('fromDisk');
        $diskNameTo = $this->argument('toDisk');

        $this->checkIfDiskExists($diskNameFrom);
        $this->checkIfDiskExists($diskNameTo);

        // $this->info(json_encode(Media::where('disk', $diskNameFrom)->first()));

        Media::where('disk', $diskNameFrom)
        ->chunk(1000, function ($medias) use ($diskNameFrom, $diskNameTo) {
          /** @var Media $media */
          foreach ($medias as $media) {
            $this->info( 'Media ' . $media['id'] . ' added to queue.' );
            dispatch(new MoveMediaToDiskJob($media, $diskNameFrom, $diskNameTo));
            $this->info( 'Media ' . $media['id'] . ' finished moving.' );
          }
        });
    }


    /**
     * Check if disks are set in the config/filesystem.
     *
     * @param $diskName
     * @throws \Exception
     */
    private function checkIfDiskExists($diskName) {
        if(!config("filesystems.disks.{$diskName}.driver")) {
            throw new \Exception("Disk driver for disk `{$diskName}` not set.");
        }
    }
}
