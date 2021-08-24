<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FileRepository;

class DownloadFileController extends Controller
{
    private $fileRepository;

    public function __construct( FileRepository $fileRepository  ){
        $this->fileRepository = $fileRepository;
    }
    public function index($id)
    {
        $downloadFile = $this->fileRepository->find($id);

        $filePath = public_path('files/'.$downloadFile->path);
        $headers = ['Content-Type: application/pdf'];
        $fileName = $downloadFile->name;
        return response()->download($filePath, $fileName, $headers);
    }
}
