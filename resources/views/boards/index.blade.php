<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Liste des boards
            <a href="{{ route('boards.create') }}" role="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">Créer une board</a>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="table-fixed">
                    <thead>
                    <tr>
                        <th class="px-4 py-2 w-1/4">Titre</th>
                        <th class="px-4 py-2 w-1/6"></th>
                        <th class="px-4 py-2 w-1/6"></th>
                        <th class="px-4 py-2 w-1/6"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($boards as $board)
                        <tr>
                            <td class="px-4 py-3">{{ $board->title }}</td>
                            <td class="px-4 py-3"><a href="{{ route('tasks.create', $board->id) }}" role="button" class="bg-green-400 hover:bg-green-500 text-white font-bold py-2 px-4 rounded">Ajouter tache</a></td>
                            <td class="px-4 py-3"><a href="{{ route('boards.show', $board->id) }}" role="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir</a></td>
                            <td class="px-4 py-3"><a href="{{ route('boards.edit', $board->id) }}" role="button" class="bg-yellow-400 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Modifier</a></td>
                            <td class="px-4 py-3">
                                <form id="destroy{{ $board->id }}" action="{{ route('boards.destroy', $board->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a role="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                       onclick="event.preventDefault();
                          this.closest('form').submit();">
                                        Supprimer
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
