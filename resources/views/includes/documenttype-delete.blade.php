<div class="modal fade" id="deleteDocumentTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="deleteDocumentTypeModalLabel{{ $type->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('types.destroy', $type->id) }}" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDocumentTypeModalLabel{{ $type->id }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong>{{ $type->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>