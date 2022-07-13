<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookManager
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function uploadImage(UploadedFile $file, $dest)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        //El nombre el fichero es el slug del original más un id único con la extensión original
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        if (!is_dir($dest)) {
            mkdir($dest, 0777);
        }

        try {
            //Aquí es donde se mueve realmente la imagen al directorio destino
            $file->move($dest, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }
}
