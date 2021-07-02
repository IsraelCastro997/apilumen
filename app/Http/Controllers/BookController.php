<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Carbon\Carbon;

class BooKController extends Controller{
    
    public function index(){
        $dataBook = Book::all();
        return response()->json($dataBook);
    }

    public function save(Request $request){
        $dataBook = new Book;

        if ($request->hasFile('image')) {
            $originalName = $request->file('image')->getClientOriginalName();
            $newName= Carbon::now()->timestamp."_".$originalName;
            $file_path='./uploads/';
            $request->file('image')->move($file_path, $newName);

            $dataBook->title = e($request->title);

            $dataBook->image = ltrim($file_path,'.').$newName;
            // $dataBook->image = $request->image;
            $dataBook->save();
        }
   
        return response()->json($newName);
    }

    public function see($id){

        $dataBook= new Book;
        $dataFound = $dataBook->find($id);

        return response()->json($dataFound);
    }

    public function delete($id){

        $dataBook= Book::find($id);
        if ($dataBook) {
            $filePath=base_path('public').$dataBook->image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $dataBook->delete();
        }

        return response()->json("Registro Borrado");
    }

    public function update(Request $request, $id){
        $dataBook= Book::find($id);
        if ($request->input('title')) {
            $dataBook->title = e($request->title);
        }
        if ($dataBook) {
            $filePath=base_path('public').$dataBook->image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $dataBook->delete();
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $newName= Carbon::now()->timestamp."_".$originalName;
        $file_path='./uploads/';
        $request->file('image')->move($file_path, $newName);

        $dataBook->image = ltrim($file_path,'.').$newName;
        // $dataBook->image = $request->image;
        $dataBook->save();

        return response()->json('datos actualizados');
    }
}