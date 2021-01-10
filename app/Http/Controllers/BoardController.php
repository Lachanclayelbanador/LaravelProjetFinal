<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;


class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $boards = Board::all();
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:100',
            'description' => 'required|max:500',
        ]);

        $board = new Board();
        $board->user_id = Auth::user()->id;
        $board->title = $request->title;
        $board->description = $request->description;
        $board->save();

        return redirect('boards');
    }

    /**
     * Display the specified resource.
     *
     * @param Board $board
     * @return Application|Factory|View|Response
     */

    public function show(Board $board)
    {

        //Je récupère l'id de tous les utilisateurs appartenant à la board affichée
        $boardUserId = $board->users->pluck('id');

        //Je récupère tous les utilisateurs de la table Users dont l'id ne correspond pas a une id d'un user présent dans ma board
        $userNotInBoard = User::whereNotIn('id',$boardUserId)
            ->get();

        //Je récupère l'inverse, à savoir tous les users DANS ma board (Variable utilisée pour display la liste d'utilisateurs dans resources/views/boards/show.blade.php)
        $userInBoard = User::whereIn('id',$boardUserId)
            ->get();

        return view('boards.show',["usersNotInBoard" => $userNotInBoard,"usersInBoard" => $userInBoard,'board' => $board]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Board $board
     * @return Application|Factory|View|Response
     */
    public function edit(Board $board)
    {
        return view('boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Board $board
     * @return RedirectResponse
     */
    public function update(Request $request, Board $board)
    {
        $board->title = $request->title;
        $board->description = $request->description;
        $board->save();

        return Redirect('boards');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Board $board
     * @return RedirectResponse
     */
    public function destroy(Board $board)
    {
        $board->delete();
        return back();
    }
}
