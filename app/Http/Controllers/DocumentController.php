<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Document;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade as PDF;
use PhpOffice\PhpWord\Reader\Word2007;


class DocumentController extends Controller
{
    public function index()
    {
        return view('document');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:doc,docx,pdf,txt',
        ]);
        if(!empty($request->document) || $request->document != ''){
            $file = $request->file('document');
            $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $file->getRealPath();
            $file->getSize();
            $file->getMimeType();
            $fileName = md5(microtime(). $file->getClientOriginalName()).'.'.$fileExtension;
            $path = base_path() . '/resources/uploads/documents/';
            $path = $file->storeAs('uploads/documents', $fileName, 'public');
            $upload = $request->file('document')->move(
                $path, $fileName
            );

            Document::create([
                'name' => $fileName,
                'extension' => $fileExtension,
            ]);
        }
        
        return redirect('/')->with('success', 'Document uploaded successfully');
    }


    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);
        $lastDocument = Document::latest()->first();
        $keyword = strtolower($request->input('keyword'));
        $searchResults = $this->searchInLastDocument($keyword);

        return view('document')->with(['searchResults' => $searchResults, 'lastDocument' => $lastDocument]);
    }

    private function searchInLastDocument($keyword)
    {
        $lastDocument = Document::latest()->first();
        if ($lastDocument) {
            

            if($lastDocument->extension == 'docx'){
                // $filePath = public_path('storage/uploads/documents/'.$lastDocument->name);

                // $fileSize = filesize($filePath);

                // $fileHandle = fopen($filePath, "r");
                // $line = @fread($fileHandle, $fileSize); 
                // $lines = explode(chr(0x0D),$line);
                // $outtext = "";
                // foreach($lines as $thisline)
                // {
                //     $pos = strpos($thisline, chr(0x00));
                //     if (($pos !== FALSE)||(strlen($thisline)==0))
                //     {
                //     } else {
                //         $outtext .= $thisline." ";
                //     }
                // }
                // $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);

                // $handle = fopen($filePath, 'r');

                // if ($handle) {
                //     $content = fread($handle, filesize($filePath));
                //     fclose($handle);

                //     $lines = explode(PHP_EOL, $content);
                //     $results = [];

                //     foreach ($lines as $lineNumber => $line) {
                //         if (stripos($line, $keyword) !== false) {
                //             $string = preg_replace_callback('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', function($char) {
                //                 return "{".dechex(ord($char[0]))."}";
                //             }, $line);

                //             $results[] = [
                //                'line_number' => $lineNumber + 1,
                //                 'content' => $string,
                //             ];
                //         }
                //     }

                //     foreach ($results as $result) {

                //         dump("Line {$result['line_number']}: {$result['content']}<br>");
                //     }
                // } else {
                //    dd(123);
                // }

                $docFilePath = public_path('storage/uploads/documents/'.$lastDocument->name);
                $phpWord = IOFactory::load($docFilePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach ($element->getElements() as $textElement) {
                                if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text .= $textElement->getText();
                                }
                            }
                        }
                    }
                }
                $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
                $searchResults = array_filter($sentences, function ($sentence) use ($keyword) {
                    return stripos($sentence, $keyword) !== false;
                });
                return $searchResults;
            }else{

                $documentPath = asset('public/storage/uploads/documents/'. $lastDocument->name); 
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($documentPath);
                $content = $pdf->getText();
                $lines = explode("\n", $content);
                $searchResults = [];
                foreach ($lines as $line) {
                    if (stripos($line, $keyword) !== false) {
                        $searchResults[] = $line;
                    }
                }
                return $searchResults;
            }
        } else {
            return ['No documents found.'];
        }
    }


}
