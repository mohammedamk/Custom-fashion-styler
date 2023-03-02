<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MoveMediaFileToDiskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $diskNameFrom;
    public $diskNameTo;
    public $filename;

    /**
     * Create a new job instance.
     *
     * @param $diskNameFrom
     * @param $diskNameTo
     * @param $filename
     */
    public function __construct($diskNameFrom, $diskNameTo, $filename)
    {
        $this->diskNameFrom = $diskNameFrom;
        $this->diskNameTo   = $diskNameTo;
        $this->filename     = $filename;
    }

    /**
     * Execute the job.
     *
     * @throws FileNotFoundException
     * @return void
     */
    public function handle() {
        $diskFrom = Storage::disk($this->diskNameFrom);
        $diskTo   = Storage::disk($this->diskNameTo);

        $diskTo->put(
          $this->filename,
          $diskFrom->readStream( $this->filename )
        );
    }
}
