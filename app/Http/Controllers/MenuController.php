<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_menu' => 'required',
            'tanggal_berlaku' => 'required|date'
        ]);

        $kalori = 450.5;
        $protein = 25.0;

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'total_kalori' => $kalori,
            'total_protein' => $protein,
            'status' => 'diajukan',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('menu-gizi.index')->with('success', 'Menu gizi berhasil disusun & diajukan!');
    }

    public function approve($id) {
        $menu = Menu::findOrFail($id);
        $menu->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id()
        ]);
        return back()->with('success', 'Menu gizi disetujui untuk didistribusikan!');
    }
}

