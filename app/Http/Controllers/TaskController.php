<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Board $board
     * @return Application|Factory|View|Response
     */
    public function index(Board $board)
    {

        return view('tasks.index', ['board' => $board]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Board $board
     * @return Application|Factory|View|Response
     */
    public function create(Board $board)
    {
    $categories = Category::all();
        return view('tasks.create', ["categories" => $categories, 'board' => $board ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $board
     * @return Application|Factory|View|RedirectResponse
     */

    public function store(Request $request, Board $board)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:100|string',
            'description' => 'required|max:500|string',
            'due_date' => 'date|after_or_equal:tomorrow',
            'category' => 'nullable|integer',
        ]);

        $task = new Task;
        $task->board_id = $board->id;
        $task->description = $validatedData['description'];
        $task->title = $validatedData['title'];
        $task->due_date = $validatedData['due_date'];
        $task->category_id = $validatedData['category'];
        $task->save();


        return redirect()->route('boards.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Application|Factory|View|Response
     */
    public function show(Board $board,
                        Task $task)
    {
        //permet de filtrer les users présents quand on veut a  jouter un utilisateur au board, seul les users qui sont dans le boards et pas déjà assigné a la tâche peuvent être add
        $TaskUserId = $task->assignedUsers->pluck('id');
        $BoardUserId = $board->users->pluck('id');
        $UserNotInTask = User::whereNotIn('id', $TaskUserId)->whereIn('id', $BoardUserId)->get();

        //$taskComment = $task->comments->pluck('id');
        //$Allcomment = Comment::whereIn('id', $taskComment)->get();

        return view('tasks.show', ['board' => $board, 'task' => $task, 'users' => $UserNotInTask]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Board $board
     * @param Task $task
     * @return Application|Factory|View|Response
     */
    public function edit(Board $board,Task $task)
    {
        return view('tasks.edit',['board' => $board , 'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Board $board
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(Request $request,Board $board,Task $task)
    {
        $task->title = $request->title;
        $task->description = $request->description;
        $task->state = $request->has('state');
        $task->save();

        return redirect()->route('tasks.index', [$board]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @param Board $board
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function destroy(Board $board,Task $task)
    {
        $task->delete();
        return back();
    }
}
