<div>
    @if ($form)
        @include('livewire.users.form')
    @else
        <div class="row pt-2">
            <div class="col-md-3">
                <button class="btn btn-dark btn-sm" wire:click="open">
                    <span class="bi bi-plus-square"></span>
                    &nbsp;User
                </button>
            </div>
            <div class="col-md-6">

            </div>
            <div class="col-md-3">
                <input type="text"class="form-control form-control-sm" placeholder="Search" wire:model.live="search">
            </div>
        </div>
        <div class="table table-responsive pt-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Sub Role</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                @hasAnyRole(['superadmin', 'admin'])
                                    <button class="btn btn-outline-warning btn-sm" wire:click="edit({{ $user->id }})">
                                        <span class="bi bi-pencil"></span>
                                    </button>
                                    @if ($user->deleted_at)
                                        <button class="btn btn-outline-success btn-sm"
                                            wire:click="restore({{ $user->id }})">
                                            <span class="bi bi-repeat"></span>
                                        </button>
                                    @else
                                        <button class="btn btn-outline-danger btn-sm"
                                            wire:click="deleteConfirm({{ $user->id }})">
                                            <span class="bi bi-trash"></span>
                                        </button>
                                    @endif
                                @endhasanyrole
                            </td>
                            <td>
                                @if ($user->deleted_at)
                                    <i class="text-decoration-line-through">
                                        {{ $user->username }}
                                        {{ $user->deleted_at ? ' Deleted ' : '' }}
                                    </i>
                                @else
                                    {{ $user->username }}
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->subrole }}</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    @endif
</div>
