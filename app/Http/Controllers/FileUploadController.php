<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
            $request->validate([

            ]);

            $fileModel = new File;
            if($request->file()) {
                $fileName = $request->file->getClientOriginalName();
                $filePath = $request->file('file')->store('uploaded', ['disk' => 'files']);
                $fileModel->name = $request->file->getClientOriginalName();
                $fileModel->path = $filePath;
                $fileModel->article_number = $request->article_number;
                $fileModel->order_position_id = $request->order_position_id;
                $fileModel->save();

                return back()
                ->with('success','Plik został dodany')
                ->with('file', $fileName);
            }

    }
}
