<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fiche d'une board
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <p class="text-2xl">Titre</p>
                <p>{{ $board->title }}</p>
                <p class="text-2xl">Description</p>
                <p>{{ $board->description }}</p>
                <p class="text-2xl">Taches assignées</p>
                <br>
                <a href="{{route('tasks.index', $board)}}" role="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir les tâches</a>
                <br>
                <br>
                <p class="text-2xl">Ajouter un utilisateur</p>
                <form action="{{route('boards.boarduser.store', $board)}}" method="POST">
                    @csrf
                    <select name="user_id" id="user_id">
                        @foreach($usersNotInBoard as $userNotInBoard)
                            <option value="{{$userNotInBoard->id}}">{{$userNotInBoard->name}} : {{$userNotInBoard->email}}</option>
                        @endforeach

                        @if($usersNotInBoard->count() == 0)
                            <option value="">Aucun utilisateur disponible</option>
                        @endif
                    </select>

                    @error('user_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button class="bg-green-400 hover:bg-green-500 text-white font-bold py-2 px-4 rounded" type="submit">Ajouter</button>
                </form>

                <p class="text-2xl">Liste des utilisateurs</p>
                <ul>
                    <!-- Pour chaque utilisateur de la board on affiche une ligne contenant les infos et un bouton delete associé a cet utilisateur car dans le meme formulaire -->
                    @foreach($board->users as $user)
                        <form action="{{route('boards.boarduser.destroy', $user->pivot)}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <li value="{{$user->id}}">{{$user->name}} : {{$user->email}}</li>
                            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" type="submit">Supprimer</button>
                        </form>
                    @endforeach
                    @error('user_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </ul>

            </div>
        </div>
    </div>
</x-app-layout>
