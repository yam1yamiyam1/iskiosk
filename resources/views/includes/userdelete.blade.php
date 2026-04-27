<div class="modal fade" id="deleteUserModal{{ $user['id'] }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.destroy', $user['id']) }}" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel{{ $user['id'] }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong>{{ $user['fname'] }} {{ $user['lname'] }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>
