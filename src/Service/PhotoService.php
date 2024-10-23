<?php 

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoService
{
    private $param;

    public function __construct(ParameterBagInterface $param)
    {
        $this->param = $param;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        $fichier = md5(uniqid(rand(),true)) . '.webp';

        $picture_infos = getimagesize($picture);
        if($picture_infos === false){
            throw new Exception('Format d\'image incorrect');
        }
        // check extension
        switch($picture_infos['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new exception('Format d\'image incorrect');
        }
        
        $photoWidth = $picture_infos[0];
        $photoHeight = $picture_infos[1];
        switch($photoWidth <=> $photoHeight){
            case -1: //portrait
                $squareSize = $photoWidth;
                $src_x=0;
                $src_y = ($photoHeight - $squareSize) /2;
                break;
            case 0: //carre
                $squareSize = $photoWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: //paysage
                $squareSize = $photoHeight;
                $src_x = ($photoWidth - $squareSize) /2;
                $src_y = 0;
        }
        $resized_photo = imagecreatetruecolor($width,$height);
        imagecopyresampled($resized_photo,$picture_source,0,0,$src_x,$src_y,$width,$height,$squareSize,$squareSize);
        $path = $this->param->get('image_directory') . $folder;

        //if !mkdir
        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/', 0755, true);
        }
        //stockage
        imagewebp($resized_photo, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);

        $picture->move($path . '/' . $fichier);
        
        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->param->get('image_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;
            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }
            $original = $path . '/' . $fichier;
            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}