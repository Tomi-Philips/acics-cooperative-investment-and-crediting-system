<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PreviewController extends Controller
{
    /**
     * Display a list of email previews
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $previewDir = storage_path('mail-previews');
        
        // Create directory if it doesn't exist
        if (!File::exists($previewDir)) {
            File::makeDirectory($previewDir, 0755, true);
        }
        
        $files = File::files($previewDir);
        $previews = [];
        
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $path = '/mail-previews/' . $filename;
            $date = substr($filename, 0, 19);
            $date = str_replace('_', ' ', $date);
            
            $previews[] = [
                'filename' => $filename,
                'path' => $path,
                'date' => $date,
            ];
        }
        
        // Sort by newest first
        usort($previews, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        
        return view('admin.mail_previews', compact('previews'));
    }
    
    /**
     * Display a specific email preview
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function show($filename)
    {
        $path = storage_path('mail-previews/' . $filename);
        
        if (!File::exists($path)) {
            abort(404);
        }
        
        $content = File::get($path);
        return response($content)->header('Content-Type', 'text/html');
    }
}