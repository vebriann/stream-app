<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    // return view global dari folder views blade
    public function index() {
        $movies = Movie::all();

        return view('admin.movies', ['movies' => $movies]);
    }

    public function create() {
        return view('admin.movie-create');
    }

    public function edit($id) {

        $movie = Movie::find($id);
        return view('admin.movie-edit', ['movie' => $movie]);
    }


    // berfungsi untuk menangkap data
    public function store(Request $request) {

        // untuk menampilkan data bisa gunakan
        // $data = $request->all();
        // untuk menghilangkan data bisa menggunakan
        // except('_token') lalu isi value yang ingin di hapus contoh di sini saya ingin hapus value _token
        $data = $request->except('_token');

        // membuat confirm validasi form
        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'large_thumbnail' => 'required|image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'
        ]);

        $smallThumbnail = $request->small_thumbnail;
        $largeThumbnail = $request->large_thumbnail;

        // Str::random(10) berfungsi untuk random string
        // $smallThumbnail->getClientOriginalName() Mengambil nama file 
        $originalSmallThumbnailName = Str::random(10).$smallThumbnail->getClientOriginalName();
        $originalLargeThumbnailName = Str::random(10).$largeThumbnail->getClientOriginalName();

        // menyimpan img
        $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
        $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);

        
        $data['small_thumbnail'] = $originalSmallThumbnailName;
        $data['large_thumbnail'] = $originalLargeThumbnailName;
        
        //  menampilkan data
        // dd($data);

        Movie::create($data);

        return redirect()->route('admin.movie')->with(['success' => 'Data Berhasil Ditambah']);
    }

    // berfungsi untuk update data
    public function update(Request $request, $id) {
        $data = $request->except('_token');

        // membuat confirm validasi form
        $request->validate([
            'title' => 'required|string',
            'small_thumbnail' => 'image|mimes:jpeg,jpg,png',
            'large_thumbnail' => 'image|mimes:jpeg,jpg,png',
            'trailer' => 'required|url',
            'movie' => 'required|url',
            'casts' => 'required|string',
            'categories' => 'required|string',
            'release_date' => 'required|string',
            'about' => 'required|string',
            'short_about' => 'required|string',
            'duration' => 'required|string',
            'featured' => 'required'
        ]);

        $movie = Movie::find($id);

        if ($request->small_thumbnail) {

            // save new images
            $smallThumbnail = $request->small_thumbnail;
            $originalSmallThumbnailName = Str::random(10).$smallThumbnail->getClientOriginalName();
            $smallThumbnail->storeAs('public/thumbnail', $originalSmallThumbnailName);
            $data['small_thumbnail'] = $originalSmallThumbnailName;

            // delete old images
            Storage::delete('public/thumbnail'.$movie->small_thumbnail);
        }

        if ($request->large_thumbnail) {
            // save new img
            $largeThumbnail = $request->large_thumbnail;
            $originalLargeThumbnailName = Str::random(10).$largeThumbnail->getClientOriginalName();
            $largeThumbnail->storeAs('public/thumbnail', $originalLargeThumbnailName);
            $data['large_thumbnail'] = $originalLargeThumbnailName;
            
            // delete old img
            Storage::delete('public/thumbnail'.$movie->large_thumbnail);

        }

        $movie->update($data);

        return redirect()->route('admin.movie')->with(['success' => 'Data Berhasil Di Update']);
    }

    public function destroy($id) {

        Movie::find($id)->delete();

        return redirect()->route('admin.movie')->with(['success' => 'Data Berhasil Di Delete']);
    }
}
