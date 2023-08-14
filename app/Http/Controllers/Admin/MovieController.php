<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    // return view global dari folder views blade
    public function index() {
        return view('admin.movies');
    }

    public function create() {
        return view('admin.movie-create');
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

        // menampilkan data
        dd($originalSmallThumbnailName . " - " .$originalLargeThumbnailName);

    }
}
