<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class compressAvatar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatar:compress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress all the avatars in the storage';

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
     * @return mixed
     */
    public function handle()
    {
        $files = Storage::files('avatars');

        $destheight = 120;
        $destwidth = 120;
        $totalPic = count($files);
        $count = 1;
        foreach($files as $file)
        {
            error_reporting(0);
            $file = "./storage/app/" . $file;
            unset($src);
            if(!filesize($file) || !getimagesize($file))
            {
                $this->warn("Process $count of $totalPic Skipped for not supported filetype");
                $count++;
                continue;
            }

            list($srcwidth, $srcheight, $image_type) = getimagesize($file);
            dump(getimagesize($file));
            switch($image_type)
            {
                case 1:
                    $src = imagecreatefromgif($file);
                    break;
                case 2:
                    $src = imagecreatefromjpeg($file);
                    break;
                case 3:
                    $src = imagecreatefrompng($file);
                    break;
                default:
                    break;

            }
            if(!isset($src))
            {
                $this->warn("Process $count of $totalPic Skipped for not supported filetype");
                $count++;
                continue;
            }

            $tmpImg = imagecreatetruecolor($destheight, $destwidth);

            imagecopyresampled($tmpImg, $src, 0, 0, 0, 0, $destwidth, $destheight,
                $srcwidth, $srcheight);

            imagepng($tmpImg, $file);

            $this->info("Process $count of $totalPic Done");
            $count++;
        }
    }
}
