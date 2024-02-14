<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$tasks = Auth::user()->tasks()->latest()->get();

		ddd($tasks);
		return view('tasks.index', compact('tasks'));
	}

	public function create() {
		return view('tasks.create');
	}

	public function store(Request $request) {
		$request->validate([
			'title' => 'required|min:3',
			'description' => 'nullable',
		]);

		Auth::user()->tasks()->create($request->all());

		return redirect()->route('tasks.index');
	}

	public function edit(Task $task) {
		$this->authorize('update', $task);
		return view('tasks.edit', compact('task'));
	}

	public function update(Request $request, Task $task) {
		$this->authorize('update', $task);

		$request->validate([
			'title' => 'required|min:3',
			'description' => 'nullable',
			'completed' => 'boolean',
		]);

		$task->update($request->all());

		return redirect()->route('tasks.index');
	}

	public function destroy(Task $task) {
		$this->authorize('delete', $task);

		$task->delete();
		return redirect()->route('tasks.index');
	}
}
