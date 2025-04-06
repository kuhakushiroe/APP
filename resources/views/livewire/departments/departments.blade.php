<div>
    @if ($form)
        @include('livewire.departments.form')
    @else
        <div class="row pt-2">
            <div class="col-md-3">
                <button class="btn btn-dark btn-sm" wire:click="open">
                    <span class="bi bi-plus-square"></span>
                    &nbsp;Department
                </button>
                @hasAnyRole(['superadmin'])
                    <button class="btn btn-danger btn-sm" wire:click="restoreAll">
                        <span class="bi bi-arrow-clockwise"></span>
                        &nbsp;Restore Delete
                    </button>
                @endhasanyrole
            </div>
            <div class="col-md-6">
                &nbsp;
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
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $data)
                        <tr>
                            <td>
                                <button class="btn btn-outline-warning btn-sm" wire:click="edit({{ $data->id }})">
                                    <span class="bi bi-pencil"></span>
                                </button>
                                @if ($data->deleted_at)
                                    <button class="btn btn-outline-success btn-sm"
                                        wire:click="restore({{ $data->id }})">
                                        <span class="bi bi-repeat"></span>
                                    </button>
                                @else
                                    <button class="btn btn-outline-danger btn-sm"
                                        wire:click="deleteConfirm({{ $data->id }})">
                                        <span class="bi bi-trash"></span>
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if ($data->deleted_at)
                                    <i class="text-decoration-line-through">
                                        {{ $data->name_department }}
                                        {{ $data->deleted_at ? ' Deleted ' : '' }}
                                    </i>
                                @else
                                    {{ $data->name_department }}
                                @endif
                            </td>
                            <td>{{ $data->description_department }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="alert alert-danger">
                                    <span class="bi bi-exclamation-circle"></span>
                                    &nbsp;No data
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $departments->links() }}
    @endif
</div>
